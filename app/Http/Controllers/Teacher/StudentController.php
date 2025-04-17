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


class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Main student query with relationships
        $query = Student::with(['enrollments.yearLevel', 'enrollments.schoolYear', 'enrollments.teacher']);

        // If user is a teacher, only show their enrolled students
        if ($user->role === 'teacher') {
            $query->whereHas('enrollments', function ($q) use ($user, $request) {
                $q->where('user_id', $user->id);

                // Apply year level filter if present
                if ($request->filled('year_level_id')) {
                    $q->where('year_level_id', $request->year_level_id);
                }

                // Apply school year filter if present
                if ($request->filled('school_year_id')) {
                    $q->where('school_year_id', $request->school_year_id);
                }
            });
        }
        // If user is admin
        elseif ($user->role === 'admin') {
            $query->where('school_info_id', $user->school_info_id);

            // Filter by specific teacher if selected
            if ($request->filled('user_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                });
            }

            // Apply year level filter if present
            if ($request->filled('year_level_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('year_level_id', $request->year_level_id);
                });
            }

            // Apply school year filter if present
            if ($request->filled('school_year_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('school_year_id', $request->school_year_id);
                });
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('firstname', 'like', "%{$searchTerm}%")
                    ->orWhere('LRN_num', 'like', "%{$searchTerm}%")
                    ->orWhere('middlename', 'like', "%{$searchTerm}%")
                    ->orWhere('lastname', 'like', "%{$searchTerm}%")
                    ->orWhere('suffix', 'like', "%{$searchTerm}%");
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
            'gender'            => 'required|string|max:255',
            'age'               => 'required|string|max:255',
            'section'           => 'nullable|string|max:255',
            'birthdate'         => 'required|date',
            'year_level_id'     => 'nullable|exists:year_levels,id',
            'school_year_id'    => 'nullable|exists:school_years,id',
            'school_info_id'    => 'nullable|exists:school_infos,id',
        ]);

        $validated['user_id'] = Auth::id();

        // ✅ Store the student and assign it to a variable
        $student = Student::create($validated);
        dd($request->all());

        // Create enrollment (separately, and explicitly use Auth::id())
        \App\Models\StudentEnrollment::create([
            'student_id'        => $student->id,
            'age'               => $student->age,
            'section'           => $student->section,
            'year_level_id'     => $validated['year_level_id'],
            'school_year_id'    => $validated['school_year_id'],
            'school_info_id'    => $validated['school_info_id'],
            'user_id'           => Auth::id(),
        ]);

        // ✅ Log the action with correct variable reference
        ActivityLogService::log(
            'Added Student',
            "Added {$student->firstname} {$student->middlename} {$student->lastname} to Grade {$student->year_level_id} - Section {$student->section}"
        );

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student created successfully.');
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

    // public function update(Request $request, Student $student)
    // {
    //     // Check again on update for security
    //     $hasAccess = StudentEnrollment::where('student_id', $student->id)
    //         ->where('user_id', Auth::id())
    //         ->exists();

    //     if (!$hasAccess) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $validated = $request->validate([
    //         'LRN_num'           => 'required|string|unique:students,LRN_num,' . $student->id,
    //         'firstname'         => 'required|string|max:255',
    //         'middlename'        => 'nullable|max:255',
    //         'lastname'          => 'required|string|max:255',
    //         'suffix'            => 'nullable|max:255',
    //         'gender'            => 'required|string|max:255',
    //         'age'               => 'required|string|max:255',
    //         'section'           => 'nullable|string|max:255',
    //         'birthdate'         => 'required|date',
    //         'year_level_id'     => 'nullable|exists:year_levels,id',
    //         'school_year_id'    => 'nullable|exists:school_years,id',
    //         'school_info_id'    => 'nullable|exists:school_infos,id',
    //     ]);

    //     $oldSection = $student->section;
    //     $oldYearLevel = $student->year_level_id;

    //     $student->update($validated);

    //     // Create enrollment (separately, and explicitly use Auth::id())
    //     \App\Models\StudentEnrollment::update([
    //         'student_id'        => $student->id,
    //         'year_level_id'     => $validated['year_level_id'],
    //         'school_year_id'    => $validated['school_year_id'],
    //         'user_id'           => Auth::id(),
    //     ]);

    //     ActivityLogService::log(
    //         'Updated Student',
    //         'Updated ' . $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname .
    //             ': Grade ' . $oldYearLevel . ' → ' . $student->year_level_id .
    //             ', Section ' . $oldSection . ' → ' . $student->section
    //     );

    //     return redirect()->route('teacher.students.index')
    //         ->with('success', 'Student updated successfully.');
    // }
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
                // Do NOT update user_id to maintain original creator
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


    // school form 09 (sf09) report card of students
    // Allow only if admin and teacher belong to the same school_info
    // public function show(Request $request, $id)
    // {
    //     $student = Student::findOrFail($id);
    //     $user = Auth::user(); // The currently logged-in user
    //     $schoolInfo = $user->schoolInfo; // Get related SchoolInfo via relationship

    //     // 🚨 Restrict access: Only allow if student and user belong to the same school
    //     if ($user->role === 'admin' || $user->role === 'teacher') {
    //         if ($student->school_info_id !== $user->school_info_id) {
    //             abort(403, 'You are not authorized to view this student\'s report card.');
    //         }
    //     }

    //     $grades = [];

    //     // Fetch class records for the current year level and school year
    //     $classRecords = ClassRecord::where('student_id', $id)
    //         ->where('year_level_id', $student->year_level_id)
    //         ->where('school_year_id', $student->school_year_id)
    //         ->with(['subject', 'quarter'])
    //         ->get();

    //     // Group records by subject and quarter
    //     foreach ($classRecords as $record) {
    //         $subject = strtolower($record->subject->name);
    //         $quarter = $record->quarter_id;
    //         $grades[$subject][$quarter] = $record->quarterly_grade;
    //     }

    //     // Calculate final ratings and remarks for each subject
    //     foreach ($grades as $subject => &$quarters) {
    //         $quarterGrades = array_filter($quarters, 'is_numeric');
    //         if (!empty($quarterGrades)) {
    //             $final = round(array_sum($quarterGrades) / count($quarterGrades));
    //             $quarters['final'] = $final;
    //             $quarters['remarks'] = $final >= 75 ? 'Passed' : 'Failed';
    //         }
    //     }

    //     // Calculate MAPEH average
    //     if (isset($grades['music']) && isset($grades['art']) && isset($grades['pe']) && isset($grades['health'])) {
    //         $mapehGrades = [
    //             'music' => $grades['music']['final'] ?? null,
    //             'art' => $grades['art']['final'] ?? null,
    //             'pe' => $grades['pe']['final'] ?? null,
    //             'health' => $grades['health']['final'] ?? null
    //         ];

    //         $validGrades = array_filter($mapehGrades, 'is_numeric');
    //         if (!empty($validGrades)) {
    //             $mapehFinal = round(array_sum($validGrades) / count($validGrades));
    //             $grades['mapeh']['final'] = $mapehFinal;
    //             $grades['mapeh']['remarks'] = $mapehFinal >= 75 ? 'Passed' : 'Failed';
    //         }
    //     }

    //     // Calculate General Average
    //     $finalGrades = [];
    //     foreach ($grades as $subject => $data) {
    //         if ($subject !== 'music' && $subject !== 'art' && $subject !== 'pe' && $subject !== 'health') {
    //             if (isset($data['final'])) {
    //                 $finalGrades[] = $data['final'];
    //             }
    //         }
    //     }

    //     $generalAverage = !empty($finalGrades) ? round(array_sum($finalGrades) / count($finalGrades)) : null;

    //     return view('teacher.students.sf09', compact('student', 'schoolInfo', 'user', 'grades', 'generalAverage'));
    // }
}
