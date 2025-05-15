<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Quarter;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ClassRecord;
use App\Models\StudentEnrollment;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;
use App\Models\AttendanceCoreValue;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Main student query with latest enrollment
        $query = Student::with(['latestEnrollment' => function ($q) {
            $q->latest(); // Eager load latest enrollment
        }]);

        // For teachers: Only show students CURRENTLY assigned to them
        if ($user->role === 'teacher') {
            $query->whereHas('student_enrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->whereRaw('student_enrollments.id = (SELECT MAX(id) FROM student_enrollments
                                  WHERE student_id = students.id)');
            });
        }

        // For admins: Filter by school & optional teacher
        elseif ($user->role === 'admin') {
            $query->where('school_info_id', $user->school_info_id)
                ->when($request->filled('user_id'), function ($q) use ($request) {
                    $q->whereHas('student_enrollments', function ($q) use ($request) {
                        $q->where('user_id', $request->user_id)
                            ->whereRaw('student_enrollments.id = (SELECT MAX(id) FROM student_enrollments
                                            WHERE student_id = students.id)');
                    });
                });
        }

        // Apply filters to BOTH roles
        $query->when($request->filled('year_level_id'), function ($q) use ($request) {
            $q->whereHas('student_enrollments', function ($q) use ($request) {
                $q->where('year_level_id', $request->year_level_id)
                    ->whereRaw('student_enrollments.id = (SELECT MAX(id) FROM student_enrollments
                                  WHERE student_id = students.id)');
            });
        })
            ->when($request->filled('school_year_id'), function ($q) use ($request) {
                $q->whereHas('student_enrollments', function ($q) use ($request) {
                    $q->where('school_year_id', $request->school_year_id)
                        ->whereRaw('student_enrollments.id = (SELECT MAX(id) FROM student_enrollments
                                  WHERE student_id = students.id)');
                });
            });

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw("LOWER(
                        CONCAT(
                            TRIM(COALESCE(lastname, '')),
                            ', ',
                            TRIM(COALESCE(firstname, '')),
                            ', ',
                            TRIM(COALESCE(middlename, '-')),
                            ', ',
                            TRIM(COALESCE(suffix, '-'))
                        )) LIKE ?", ["%{$searchTerm}%"])
                    ->orWhere('LRN_num', 'like', "%{$searchTerm}%")
                    ->orWhere('gender', 'like', "%{$searchTerm}%")
                    ->orWhere('age', 'like', "%{$searchTerm}%")
                    ->orWhere('section', 'like', "%{$searchTerm}%");
            });
        }

        // Get enrollments for the current teacher
        $enrollmentsQuery = StudentEnrollment::with(['student', 'yearLevel', 'schoolYear'])
            ->where('user_id', $user->id);

        // Apply filters to enrollments query if present
        if ($request->filled('year_level_id')) {
            $enrollmentsQuery->where('year_level_id', $request->year_level_id);
        }
        if ($request->filled('school_year_id')) {
            $enrollmentsQuery->where('school_year_id', $request->school_year_id);
        }

        $enrollments = $enrollmentsQuery->orderBy('student_id')
            ->orderByDesc('year_level_id')
            ->orderByDesc('school_year_id')
            ->get()
            ->unique('student_id');

        // Other needed data
        $students = $query->paginate(50)->withQueryString();
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $teachers = User::where('role', 'teacher')
            ->when($user->role === 'admin', function ($q) use ($user) {
                $q->where('school_info_id', $user->school_info_id);
            })
            ->get();

        return view('teacher.students.index', compact(
            'students',
            'yearLevels',
            'schoolYears',
            'teachers',
            'user',
            'enrollments'
        ));
    }

    // Show the form for creating a new student
    public function create()
    {
        // Check if the authenticated teacher is linked to this student
        $hasAccess = User::where('role', 'teacher')
            ->where('id', Auth::id())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();

        // Retrieve year levels for the dropdown selection
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $school_infos = SchoolInfo::all();
        // Get the authenticated user instead of all users

        return view('teacher.students.create', compact('yearLevels', 'schoolYears', 'user', 'school_infos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'LRN_num'           => 'required|string|unique:students,LRN_num',
            'firstname'         => 'required|string|max:255',
            'middlename'        => 'nullable|max:255',
            'lastname'          => 'required|string|max:255',
            'suffix'            => 'nullable|max:255',
            'gender'            => 'required|string|in:male,female',
            'age'               => 'required|integer|min:5|max:60',
            'section'           => 'required|string|max:255',
            'birthdate'         => 'required|date|before_or_equal:-5 years|after_or_equal:-60 years',
            'year_level_id'     => 'required|exists:year_levels,id',
            'school_year_id'    => 'required|exists:school_years,id',
            'school_info_id'    => 'required|exists:school_infos,id',
        ]);

        // Double-check age calculation from birthdate
        $birthdate = new \DateTime($request->birthdate);
        $today = new \DateTime();
        $calculatedAge = $today->diff($birthdate)->y;

        if ($calculatedAge < 5 || $calculatedAge > 60) {
            return back()
                ->withErrors(['age' => 'Calculated age must be between 5 and 60 years'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $validated['user_id'] = Auth::id();
            $student = Student::create($validated);

            \App\Models\StudentEnrollment::create([
                'student_id'        => $student->id,
                'age'               => $student->age,
                'section'           => $student->section,
                'year_level_id'     => $student->year_level_id,
                'school_year_id'    => $student->school_year_id,
                'school_info_id'    => $student->school_info_id,
                'user_id'           => Auth::id(),
            ]);

            DB::commit();

            ActivityLogService::log(
                'Added Student',
                sprintf(
                    "Added %s %s %s %s to Grade %s - Section %s",
                    $student->firstname,
                    $student->middlename,
                    $student->lastname,
                    $student->suffix,
                    $student->yearLevel->name,
                    $student->section
                )
            );

            return redirect()->route('teacher.students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to create student. Please try again.'])
                ->withInput();
        }
    }

    public function edit(Student $student)
    {
        // Check if the authenticated teacher is linked to this student
        $hasAccess = StudentEnrollment::where('student_id', $student->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $school_infos = SchoolInfo::all();

        return view('teacher.students.edit', compact('student', 'yearLevels', 'schoolYears', 'school_infos'));
    }

    public function update(Request $request, Student $student)
    {
        // Re-verify access before update
        $hasAccess = StudentEnrollment::where('student_id', $student->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'LRN_num'           => 'required|string|unique:students,LRN_num,' . $student->id,
            'firstname'         => 'required|string|max:255',
            'middlename'        => 'nullable|max:255',
            'lastname'          => 'required|string|max:255',
            'suffix'            => 'nullable|max:255',
            'gender'            => 'required|string|max:255',
            'age'               => 'required|string|max:255',
            'section'           => 'nullable|string|max:255',
            'birthdate'         => 'required|date',
            'year_level_id'     => 'nullable|exists:year_levels,id',
            'school_year_id'    => 'nullable|exists:school_years,id',
            'school_info_id'    => 'nullable|exists:school_infos,id',
        ]);

        // Store old values for logging
        $oldSection = $student->section;
        $oldYearLevel = $student->year_level_id;

        // Update student record
        $student->update($validated);

        // Update associated enrollment
        $enrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'year_level_id'     => $validated['year_level_id'],
                'school_year_id'    => $validated['school_year_id'],
                'school_info_id'    => $validated['school_info_id'],
            ]);
        }

        ActivityLogService::log(
            'Updated Student',
            'Updated ' . $student->full_name .
                ': Grade ' . ($oldYearLevel ?? 'N/A') . ' → ' . ($student->year_level_id ?? 'N/A') .
                ', Section ' . ($oldSection ?? 'N/A') . ' → ' . ($student->section ?? 'N/A')
        );

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Check if the teacher has any enrollments for this student
        $hasEnrollments = StudentEnrollment::where('student_id', $student->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$hasEnrollments) {
            abort(403, 'Unauthorized action.');
        }

        // Delete only the enrollments owned by this teacher
        StudentEnrollment::where('student_id', $student->id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student removed from your class successfully.');
    }

    // StudentController.php
    public function show(Request $request, Student $student)
    {
        $user = Auth::user();
        // Get filters from URL parameters
        $yearLevelId = $request->query('yearLevel');
        $schoolYearId = $request->query('schoolYear');

        // Verify enrollment exists and belongs to current user's school
        $enrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('year_level_id', $yearLevelId)
            ->where('school_year_id', $schoolYearId)
            ->with('yearLevel', 'schoolYear', 'student')
            ->firstOrFail();

        // Authorization check
        // 🚨 Restrict access: Only allow if student and user belong to the same school
        if ($user->role !== 'admin' && $enrollment->school_info_id !== $user->school_info_id) {
            abort(403, 'You are not authorized to view this student\'s report card.');
        }

        // Get grades for this specific enrollment
        $classRecords = ClassRecord::where('student_id', $student->id)
            ->where('year_level_id', $yearLevelId)
            ->where('school_year_id', $schoolYearId)
            ->with(['subject', 'quarter'])
            ->get();


        $grades = [];
        // Group records by subject and quarter
        foreach ($classRecords as $record) {
            $subject = strtolower($record->subject->name);
            $quarter = $record->quarter_id;
            $grades[$subject][$quarter] = $record->quarterly_grade;
        }

        // Calculate final ratings and remarks for each subject
        foreach ($grades as $subject => &$quarters) {
            $quarterGrades = array_filter($quarters, 'is_numeric');
            if (!empty($quarterGrades)) {
                $final = round(array_sum($quarterGrades) / count($quarterGrades));
                $quarters['final'] = $final;
                $quarters['remarks'] = $final >= 75 ? 'Passed' : 'Failed';
            }
        }

        // Calculate MAPEH average
        if (isset($grades['music']) && isset($grades['art']) && isset($grades['pe']) && isset($grades['health'])) {
            $mapehGrades = [
                'music' => $grades['music']['final'] ?? null,
                'art' => $grades['art']['final'] ?? null,
                'pe' => $grades['pe']['final'] ?? null,
                'health' => $grades['health']['final'] ?? null
            ];

            $validGrades = array_filter($mapehGrades, 'is_numeric');
            if (!empty($validGrades)) {
                $mapehFinal = round(array_sum($validGrades) / count($validGrades));
                $grades['mapeh']['final'] = $mapehFinal;
                $grades['mapeh']['remarks'] = $mapehFinal >= 75 ? 'Passed' : 'Failed';
            }
        }

        // Calculate General Average
        $finalGrades = [];
        foreach ($grades as $subject => $data) {
            if ($subject !== 'music' && $subject !== 'art' && $subject !== 'pe' && $subject !== 'health') {
                if (isset($data['final'])) {
                    $finalGrades[] = $data['final'];
                }
            }
        }

        $generalAverage = !empty($finalGrades) ? round(array_sum($finalGrades) / count($finalGrades)) : null;


        // Get attendance records
        $attendance = AttendanceCoreValue::where('student_enrollment_id', $enrollment->id)->first();

        // Get core values
        $coreValues = [
            'maka_diyos' => [
                'statement' => 'Expresses one\'s spiritual beliefs while respecting the spiritual beliefs of others',
                'quarters' => [
                    'q1' => $attendance->maka_diyos_q1 ?? null,
                    'q2' => $attendance->maka_diyos_q2 ?? null,
                    'q3' => $attendance->maka_diyos_q3 ?? null,
                    'q4' => $attendance->maka_diyos_q4 ?? null,
                ]
            ],
            'makatao' => [
                'statement' => 'Shows adherence to ethical principles by upholding truth',
                'quarters' => [
                    'q1' => $attendance->makatao_q1 ?? null,
                    'q2' => $attendance->makatao_q2 ?? null,
                    'q3' => $attendance->makatao_q3 ?? null,
                    'q4' => $attendance->makatao_q4 ?? null,
                ]
            ],
            'maka_kalikasan' => [
                'statement' => 'Cares for the environment and utilizes resources wisely, judiciously, and economically',
                'quarters' => [
                    'q1' => $attendance->maka_kalikasan_q1 ?? null,
                    'q2' => $attendance->maka_kalikasan_q2 ?? null,
                    'q3' => $attendance->maka_kalikasan_q3 ?? null,
                    'q4' => $attendance->maka_kalikasan_q4 ?? null,
                ]
            ],
            'makabansa' => [
                'statement' => 'Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen',
                'quarters' => [
                    'q1' => $attendance->makabansa_q1 ?? null,
                    'q2' => $attendance->makabansa_q2 ?? null,
                    'q3' => $attendance->makabansa_q3 ?? null,
                    'q4' => $attendance->makabansa_q4 ?? null,
                ]
            ],
            'makabansa' => [
                'statement' => 'Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen',
                'quarters' => [
                    'q1' => $attendance->makabansa_q1 ?? null,
                    'q2' => $attendance->makabansa_q2 ?? null,
                    'q3' => $attendance->makabansa_q3 ?? null,
                    'q4' => $attendance->makabansa_q4 ?? null,
                ]
            ],
        ];

        return view('teacher.students.sf09', [
            'student' => $student,
            'enrollment' => $enrollment,
            'grades' => $grades,
            'generalAverage' => $generalAverage,
            'attendance' => $attendance,
            'coreValues' => $coreValues
        ]);
    }
}
