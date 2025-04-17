<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Quarter;
use App\Http\Controllers\Controller;
use App\Models\ClassRecord;
use App\Models\SchoolInfo;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SchoolForm10Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all students from year level 1 to 6
        $students = Student::whereBetween('year_level_id', [1, 6])->get();
        $schoolInfo = SchoolInfo::get()->first();
        $yearLevel = YearLevel::all();

        // Get the selected student ID from the request
        $selectedStudentId = $request->input('student_id');
        $selectedStudent = null;
        $grades = [];

        if ($selectedStudentId) {
            // Fetch the selected student (Handle missing student gracefully)
            $selectedStudent = Student::where('student_id', $selectedStudentId)->first();

            if (!$selectedStudent) {
                return redirect()->route('teacher.school-forms-10.index')->withErrors('Student not found.');
            }

            // Fetch class records for the selected student
            $classRecords = ClassRecord::where('student_id', $selectedStudentId)
                ->with(['subject', 'quarter'])
                ->get();

            // Group records by subject and quarter
            foreach ($classRecords as $record) {
                if ($record->subject) {
                    $subject = strtolower($record->subject->name);
                    $quarter = $record->quarter_id;
                    $grades[$subject][$quarter] = $record->quarterly_grade;
                    $grades[$subject]['final'] = $record->final_grade ?? null;
                }
            }
        }

        return view('teacher.school-forms-10.index', compact('students', 'selectedStudent', 'grades', 'yearLevel', 'schoolInfo'));
    }

    // this function is use to show the school form 10 from grade 1 to 6 where student has a record
    // and the student is not a transferee, previously enrolled, or a new student
    // Allow only if admin and teacher belong to the same school_info

    public function show(Request $request, $student_id)
    {
        // Get student by student_id (should still refer to 'students.id')
        $student = Student::where('id', $student_id)->firstOrFail();
        $user = Auth::user();
        $schoolInfo = SchoolInfo::first();

        // Restrict access
        if (in_array($user->role, ['admin', 'teacher']) && $student->school_info_id !== $user->school_info_id) {
            abort(403, 'You are not authorized to view this student\'s report card.');
        }

        // Get ALL enrollments (Grade 1 to 6) for this student
        $enrollments = StudentEnrollment::where('student_id', $student_id)
            ->with(['yearLevel', 'schoolYear', 'teacher'])
            ->get();

        $gradesByYearLevel = [];

        foreach ($enrollments as $enrollment) {
            $classRecords = ClassRecord::where('student_id', $student_id)
                ->where('year_level_id', $enrollment->year_level_id)
                ->where('school_year_id', $enrollment->school_year_id)
                ->with(['subject', 'quarter'])
                ->get();

            $grades = [];

            foreach ($classRecords as $record) {
                $subject = strtolower($record->subject->name);
                $quarter = $record->quarter_id;
                $grades[$subject][$quarter] = $record->quarterly_grade;
            }

            foreach ($grades as $subject => &$quarters) {
                $quarterGrades = array_filter($quarters, 'is_numeric');
                if (!empty($quarterGrades)) {
                    $final = round(array_sum($quarterGrades) / count($quarterGrades));
                    $quarters['final'] = $final;
                    $quarters['remarks'] = $final >= 75 ? 'Passed' : 'Failed';
                }
            }

            // MAPEH average calculation
            if (isset($grades['music'], $grades['art'], $grades['pe'], $grades['health'])) {
                $mapehFinal = round(array_sum(array_filter([
                    $grades['music']['final'] ?? 0,
                    $grades['art']['final'] ?? 0,
                    $grades['pe']['final'] ?? 0,
                    $grades['health']['final'] ?? 0,
                ], 'is_numeric')) / 4);

                $grades['mapeh']['final'] = $mapehFinal;
                $grades['mapeh']['remarks'] = $mapehFinal >= 75 ? 'Passed' : 'Failed';
            }

            // General average
            $finalGrades = array_filter(array_map(fn($g) => $g['final'] ?? null, $grades));
            $generalAverage = !empty($finalGrades) ? round(array_sum($finalGrades) / count($finalGrades)) : null;

            // Save all into array per year level
            $gradesByYearLevel[$enrollment->year_level_id] = [
                'grades' => $grades,
                'generalAverage' => $generalAverage,
                'enrollment' => $enrollment
            ];
        }

        return view('teacher.school-forms-10.sf10', compact(
            'student',
            'user',
            'schoolInfo',
            'enrollments',
            'gradesByYearLevel'
        ));
    }

}
