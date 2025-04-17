<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRecord;
use App\Models\Student;
use App\Models\Quarter;
use App\Models\User;
use App\Models\YearLevel;
use App\Models\SchoolYear;

class SummaryQuarterlyGradesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query for students with their class records
        $query = Student::with(['classRecords' => function ($query) use ($request) {
            $query->with('quarter'); // Include related models

            // Apply quarter filter directly to classRecords
            if ($quarterId = $request->input('quarter_id')) {
                $query->where('quarter_id', $quarterId);
            }
        }]);

        // Ensure that admins and teachers can only view records from their own school
        if ($user->role === 'teacher') {
            // Teachers can only view their own students
            $query->whereHas('classRecords', function ($q) use ($user) {
                $q->where('user_id', $user->id) // Ensure the teacher is the one associated with the class record
                    ->whereHas('user', function ($query) use ($user) {
                        $query->where('school_info_id', $user->school_info_id); // Teacher's school info
                    });
            });
        } elseif ($user->role === 'admin') {
            // Admin can view all records within their own school only
            $query->whereHas('classRecords', function ($q) use ($user) {
                $q->whereHas('user', function ($query) use ($user) {
                    $query->where('school_info_id', $user->school_info_id); // Admin's school info
                });
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

        // Apply school year filter directly to classRecords
        if ($schoolYearId = $request->input('school_year_id')) {
            $query->where('school_year_id', $schoolYearId);
        }

        // Apply pagination: adjust the number (25) as desired.
        $students = $query->paginate(25);

        // Fetch dropdown data for filters
        $yearLevels = YearLevel::all();
        $sections = User::whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section');
        $teachers = User::where('role', 'teacher')->get();
        $quarters = Quarter::all();
        $schoolYears = SchoolYear::all();

        return view('teacher.summary_quarterly_grades.index', [
            'students'          => $students,
            'yearLevels'        => $yearLevels,
            'sections'          => $sections,
            'quarters'          => $quarters,
            'schoolYears'       => $schoolYears,
            'teachers'          => $teachers,
            'selectedTeacher'   => $request->input('user_id'),
            'selectedYear'      => $request->input('year_level_id'),
            'selectedSection'   => $request->input('section'),
            'selectedQuarter'   => $request->input('quarter_id'),
            'selectedSchoolYear' => $request->input('school_year_id'),
        ]);
    }
}
