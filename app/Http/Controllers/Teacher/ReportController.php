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
        // Get high honors students (90-100)
        $highHonors = Student::with(['classRecords' => function ($query) {
            $query->selectRaw('student_id, AVG(quarterly_grade) as average_grade')
                ->groupBy('student_id');
        }])
            ->get()
            ->filter(function ($student) {
                return $student->classRecords->first()->average_grade >= 90 &&
                    $student->classRecords->first()->average_grade <= 100;
            });

        // Get students below 75
        $needsImprovement = Student::with(['classRecords' => function ($query) {
            $query->selectRaw('student_id, AVG(quarterly_grade) as average_grade')
                ->groupBy('student_id');
        }])
            ->get()
            ->filter(function ($student) {
                return $student->classRecords->first()->average_grade < 75;
            });

        return view('teacher.reports.students-report', compact('highHonors', 'needsImprovement'));
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
