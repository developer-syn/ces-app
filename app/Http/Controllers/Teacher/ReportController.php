<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassRecord;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class ReportController extends Controller
{
    public function gradeReports()
    {
        // Base query with access control
        $query = Student::with(['classRecords' => function ($query) {
            $query->selectRaw('student_id, AVG(quarterly_grade) as average_grade')
                ->groupBy('student_id');
        }]);

        // Restrict to assigned students for non-admins
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $students = $query->get();

        // Filter students
        $highHonors = $students->filter(function ($student) {
            $averageGrade = optional($student->classRecords->first())->average_grade ?? 0;
            return $averageGrade >= 90 && $averageGrade <= 100;
        });

        $needsImprovement = $students->filter(function ($student) {
            $averageGrade = optional($student->classRecords->first())->average_grade ?? 0;
            return $averageGrade < 75;
        });

        return view('teacher.reports.students-report', compact('students', 'highHonors', 'needsImprovement'));
    }

    public function downloadGradeReports()
    {
        // Repeat the same data fetching logic
        $students = Student::with(['classRecords' => function ($query) {
            $query->select('student_id')
                ->selectRaw('AVG(quarterly_grade) as average_grade')
                ->groupBy('student_id');
        }])->get();

        $highHonors = $students->filter(function ($student) {
            return optional($student->classRecords->first())->average_grade >= 90;
        });

        $needsImprovement = $students->filter(function ($student) {
            return optional($student->classRecords->first())->average_grade < 75;
        });

        $pdf = \PDF::loadView('teacher.reports.pdf-grade-report', compact('highHonors', 'needsImprovement'));
        return $pdf->download('student-grade-report.pdf');
    }
}
