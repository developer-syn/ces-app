<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRecord;
use App\Models\YearLevel;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassRecord::distinct('subject_id')->count();
        $totalSubjects = Subject::count();

        // Count students per year level
        $studentsPerYearLevel = YearLevel::withCount('students')->get();

        $recentActivities = [
            'Promoted John Doe to Grade 5',
            'Added a new teacher: Jane Smith',
            'Updated class schedule for Grade 6',
        ];

        return view('dashboard', compact('totalStudents', 'totalTeachers', 'totalClasses', 'studentsPerYearLevel', 'recentActivities', 'totalSubjects'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
