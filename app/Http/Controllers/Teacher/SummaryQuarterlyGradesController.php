<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRecord;
use App\Models\Student;
use App\Models\Quarter;
use App\Models\User;
use App\Models\YearLevel;

class SummaryQuarterlyGradesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query for students with their class records
        $query = Student::with(['classRecords' => function ($query) {
            $query->with('subject', 'quarter', 'user'); // Use 'user' instead of 'teacher'
        }]);

        // Filter records based on the user's role
        if ($user->role === 'teacher') {
            // Teachers can only view their own students
            $query->whereHas('classRecords', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // 1) Year Level filter
        if ($yearLevel = $request->input('year_level_id')) {
            $query->where('year_level_id', $yearLevel);
        }

        // 2) Section filter
        if ($section = $request->input('section')) {
            $query->where('section', $section);
        }

        // 3) Teacher filter (for admin to filter by teacher)
        if ($teacherId = $request->input('user_id')) {
            $query->whereHas('classRecords', function ($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            });
        }

        // 4) Quarter filter
        if ($quarterId = $request->input('quarter_id')) {
            $query->whereHas('classRecords', function ($q) use ($quarterId) {
                $q->where('quarter_id', $quarterId);
            });
        }

        // Fetch the filtered students
        $students = $query->get();

        // Fetch dropdown data for filters
        $yearLevels = YearLevel::all();
        $sections = Student::distinct()->pluck('section');
        $teachers = User::where('role', 'teacher')->get();
        $quarters = Quarter::all();

        return view('teacher.summary_quarterly_grades.index', [
            'students'          => $students,
            'yearLevels'        => $yearLevels,
            'sections'          => $sections,
            'teachers'          => $teachers,
            'quarters'          => $quarters,
            'selectedYear'      => $request->input('year_level_id'),
            'selectedSection'   => $request->input('section'),
            'selectedTeacher'   => $request->input('teacher_id'),
            'selectedQuarter'   => $request->input('quarter_id'),
        ]);
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
