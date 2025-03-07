<?php

namespace App\Http\Controllers\Admin;

use App\Models\YearLevel;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Display a listing of students
    public function index()
    {
        // Retrieve students with their associated year level (assuming a relation is defined)
        $yearLevels = YearLevel::all();
        $students = Student::with('yearLevel')->get();

        return view('admin.students.index', compact('students', 'yearLevels'));
    }

    // Show the form for creating a new student
    public function create()
    {
        // Retrieve year levels for the dropdown selection
        $yearLevels = YearLevel::all();
        return view('admin.students.create', compact('yearLevels'));
    }

    // Store a newly created student in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'birthdate'     => 'required|date',
            'year_level_id' => 'required|exists:year_levels,id',
        ]);

        Student::create($validated);

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student created successfully.');
    }

    // Display the specified student
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    // Show the form for editing the specified student
    public function edit(Student $student)
    {
        $yearLevels = YearLevel::all();
        return view('admin.students.edit', compact('student', 'yearLevels'));
    }

    // Update the specified student in storage
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'birthdate'     => 'required|date',
            'year_level_id' => 'required|exists:year_levels,id',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student updated successfully.');
    }

    // Remove the specified student from storage
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student deleted successfully.');
    }
}
