<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ClassRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $query = Student::with(['yearLevel', 'schoolYear']);

        // Filter students based on role
        if ($user->role === 'teacher') {
            // Teachers only see their students
            $query->where('user_id', $user->id);
        }
        // Apply search filter
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%");
        }
        // Apply year level filter
        if ($request->has('year_level')) {
            $yearLevel = $request->input('year_level');
            $query->where('year_level_id', $yearLevel);
        }
        // Apply school year filter
        if ($request->has('school_year')) {
            $schoolYear = $request->input('school_year');
            $query->where('school_year_id', $schoolYear);
        }
        // Apply pagination to the filtered query
        $students = $query->paginate(25)->withQueryString();
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();

        return view('teacher.students.index', compact(
            'students',
            'yearLevels',
            'schoolYears',
        ));
    }

    // Show the form for creating a new student
    public function create()
    {
        // Retrieve year levels for the dropdown selection
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $user = auth()->user(); // Get the authenticated user instead of all users

        return view('teacher.students.create', compact('yearLevels', 'schoolYears', 'user'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'LRN_num'           => 'required|string|unique:students,LRN_num',
            'name'              => 'required|string|max:255',
            'gender'            => 'required|string|max:255',
            'section'           => 'required|string|max:255',
            'birthdate'         => 'required|date',
            'year_level_id'     => 'required|exists:year_levels,id',
            'school_year_id'    => 'required|exists:school_years,id',
            // other validations...
        ]);

        $validated['user_id'] = Auth::id();

        Student::create($validated);

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
            'LRN_num' => 'required|string||unique:students,LRN_num,' . $student->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'year_level_id' => 'required|exists:year_levels,id',
            // add other validations as needed
        ]);

        $student->update($validated);

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

    public function show($id)
    {
        $student = Student::findOrFail($id);

        // Example: Retrieve grades from a `grades` table
        // (Adjust logic as needed for your database structure)
        $grades = ClassRecord::where('student_id', $id)->get();

        return view('teacher.students.sf09', compact('student', 'grades'));
    }

    public function sf10($id)
    {
        $student = Student::findOrFail($id);

        // SF10 details (transcript, etc.)
        // We'll fill this in once you provide details or data structure
        // For now, just pass the $student
        return view('teacher.students.sf10', compact('student'));
    }
}
