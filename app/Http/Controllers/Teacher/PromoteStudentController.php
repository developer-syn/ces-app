<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\ClassRecord;
use App\Models\Student;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentEnrollment;

class PromoteStudentController extends Controller
{
    public function promote(Request $request, Student $student)
    {
        $query = Student::with(['enrollments.yearLevel', 'enrollments.schoolYear', 'enrollments.teacher']);

        // Apply filters
        if ($request->has('year_level_id') && $request->year_level_id) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('year_level_id', $request->year_level_id);
            });
        }

        if ($request->has('school_year_id') && $request->school_year_id) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('school_year_id', $request->school_year_id);
            });
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->whereHas('enrollments', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $students = $query->get();

        // Get filter options
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $teachers = User::where('role', 'teacher')->get();

        // Get the current enrollment (latest one)
        $currentEnrollment = $student->enrollments()->latest()->first();
        if (!$currentEnrollment) {
            return back()->with('error', 'Student has no enrollment records.');
        }

        $currentYearLevel = $currentEnrollment->year_level_id;
        $schoolInfoId = $currentEnrollment->school_info_id;

        // Find the next year level
        $nextYearLevel = YearLevel::where('id', '>', $currentYearLevel)->orderBy('id')->first();
        if (!$nextYearLevel) {
            return back()->with('error', 'The student is already in the highest year level.');
        }

        // Ensure all grades for 1st to 4th quarters are completed
        $quarters = [1, 2, 3, 4];
        $completedQuarters = ClassRecord::where('student_id', $student->id)
            ->whereIn('quarter_id', $quarters)
            ->whereNotNull('quarterly_grade')
            ->distinct('quarter_id')
            ->pluck('quarter_id')
            ->toArray();

        if (array_diff($quarters, $completedQuarters)) {
            return back()->with('error', 'The student cannot be promoted as not all quarter grades are completed.');
        }

        // Get or create the next school year
        $currentSchoolYear = $currentEnrollment->school_year_id;
        $nextSchoolYear = SchoolYear::where('id', '>', $currentSchoolYear)->orderBy('id')->first();
        if (!$nextSchoolYear) {
            $currentSchoolYearRecord = SchoolYear::find($currentSchoolYear);
            if ($currentSchoolYearRecord && preg_match('/(\d{4})\s*-\s*(\d{4})/', $currentSchoolYearRecord->name, $matches)) {
                $nextSchoolYearName = (intval($matches[1]) + 1) . ' - ' . (intval($matches[2]) + 1);
                $nextSchoolYear = SchoolYear::create(['name' => $nextSchoolYearName, 'current' => false]);
            } else {
                return back()->with('error', 'Failed to determine the next school year.');
            }
        }

        // Assign the student to a new teacher
        $nextTeacher = User::find($request->input('user_id'));
        if (!$nextTeacher) {
            return back()->with('error', 'The selected teacher does not exist.');
        }

        // Create a new enrollment record
        StudentEnrollment::create([
            'student_id' => $student->id,
            'year_level_id' => $nextYearLevel->id,
            'school_year_id' => $nextSchoolYear->id,
            'user_id' => $nextTeacher->id,
            'school_info_id' => $schoolInfoId,
        ]);

        return redirect()->route('teacher.students.index', compact('students', 'yearLevels', 'schoolYears', 'teachers'))
            ->with('success', "Student promoted to {$nextYearLevel->name} for the school year {$nextSchoolYear->name} and assigned to {$nextTeacher->name} successfully.");
    }
}
