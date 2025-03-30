<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Quarter;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ClassRecord;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;



class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Student::query();

        // If user is a teacher, they can only see their students
        if ($user->role === 'teacher') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin' && $request->has('user_id') && $request->input('user_id') !== '') {
            // Admins can filter by teacher
            $query->where('user_id', $request->input('user_id'));
        }

        // Apply search filter
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

        // Apply year level and school year filters
        if ($request->filled('year_level_id')) {
            $query->where('year_level_id', $request->input('year_level_id'));
        }

        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->input('school_year_id'));
        }

        // Paginate results
        $students = $query->paginate(25)->withQueryString();
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('teacher.students.index', compact('students', 'yearLevels', 'schoolYears', 'teachers'));
    }


    // Show the form for creating a new student
    public function create()
    {
        // Retrieve year levels for the dropdown selection
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
         // Get the authenticated user instead of all users
        $user = auth()->user();

        return view('teacher.students.create', compact('yearLevels', 'schoolYears', 'user'));
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
        ]);

        $validated['user_id'] = Auth::id();

        // ✅ Store the student and assign it to a variable
        $student = Student::create($validated);

        // ✅ Log the action with correct variable reference
        ActivityLogService::log(
            'Added Student',
            "Added {$student->firstname} {$student->middlename} {$student->lastname} to Grade {$student->year_level_id} - Section {$student->section}"
        );

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student created successfully.');
    }



    // For methods like edit, update, destroy, verify the student belongs to the teacher:
    public function edit(Student $student)
    {
        if ($student->user_id !== Auth::id()) {  // Use user_id instead of teacher_id
            abort(403, 'Unauthorized action.');
        }
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        return view('teacher.students.edit', compact('student', 'yearLevels', 'schoolYears'));
    }


    public function update(Request $request, Student $student)
    {
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
        ]);

        // ✅ Get old values before updating
        $oldSection = $student->section;
        $oldYearLevel = $student->year_level_id;

        // ✅ Update the student record
        $student->update($validated);

        // ✅ Log the action
        ActivityLogService::log(
            'Updated Student',
            'Updated ' . $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname .
                ': Grade ' . $oldYearLevel . ' → ' . $student->year_level_id .
                ', Section ' . $oldSection . ' → ' . $student->section
        );


        return redirect()->route('teacher.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $student->delete();

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    // school form 09 (sf09) report card of students
    public function show(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $schoolInfo = SchoolInfo::get()->first();
        $grades = [];

        // Fetch class records for the current year level and school year
        $classRecords = ClassRecord::where('student_id', $id)
            ->where('year_level_id', $student->year_level_id)
            ->where('school_year_id', $student->school_year_id)
            ->with(['subject', 'quarter'])
            ->get();

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

        return view('teacher.students.sf09', compact('student', 'schoolInfo', 'grades', 'generalAverage'));
    }
}
