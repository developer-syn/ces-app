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
            $query->with('quarter')
                // Add these filters to eager loading
                ->when($request->input('quarter_id'), function ($q) use ($request) {
                    $q->where('quarter_id', $request->quarter_id);
                })
                ->when($request->input('school_year_id'), function ($q) use ($request) {
                    $q->where('school_year_id', $request->school_year_id);
                });
        }]);

        // Ensure that admins and teachers can only view records from their own school
        if ($user->role === 'teacher') {
            // If NO school year filter is applied, restrict to current year/school year
            if (!$request->filled('school_year_id')) {
                $query->where('year_level_id', $user->year_level_id)
                      ->where('school_year_id', $user->school_year_id);
            }

            // Always restrict to class records created by this teacher
            $query->whereHas('classRecords', function ($q) use ($user, $request) {
                $q->where('user_id', $user->id) // Teacher's own records
                  ->when($request->filled('school_year_id'), function ($q) use ($request) {
                      $q->where('school_year_id', $request->school_year_id);
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
        // Filter by school year IN CLASS RECORDS (not student's current year)
        if ($yearLevel = $request->input('year_level_id')) {
            $query->whereHas('classRecords', function ($q) use ($yearLevel) {
                $q->where('year_level_id', $yearLevel);
            });
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

        // Apply school year filter directly to classRecords ensure that the filter is applied as well to the teacher who is assigned
        // Filter by school year IN CLASS RECORDS (not student's current year)
        if ($schoolYearId = $request->input('school_year_id')) {
            $query->whereHas('classRecords', function ($q) use ($schoolYearId) {
                $q->where('school_year_id', $schoolYearId);
            });
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
