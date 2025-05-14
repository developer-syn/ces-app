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
            $query->with('subject'); // Load subject relationship
        }]);

        // Restrict to assigned students for non-admins
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $students = $query->get()->map(function ($student) {
            // Calculate average using your formula for each subject
            $grades = $student->classRecords->map(function ($record) {
                // Your calculation logic for each subject's grade
                $wwScores = json_decode($record->written_works_scores, true) ?? array_fill(0, 10, 0);
                $ptScores = json_decode($record->performance_tasks_scores, true) ?? array_fill(0, 10, 0);
                $qaScore = $record->quarterly_assessment_score ?? 0;

                $hwwArray = json_decode($record->hww_scores, true) ?? array_fill(0, 10, 1);
                $hptArray = json_decode($record->hpt_scores, true) ?? array_fill(0, 10, 1);
                $globalHqa = max($record->global_hqa_score ?? 1, 1);

                $totalWrittenWorks = array_sum($wwScores);
                $totalPerformanceTasks = array_sum($ptScores);
                $hwwTotalGlobal = max(array_sum($hwwArray), 1);
                $hptTotalGlobal = max(array_sum($hptArray), 1);

                $psWrittenWorks = round(($totalWrittenWorks / $hwwTotalGlobal) * 100, 2);
                $wsWrittenWorks = round($psWrittenWorks * 0.30, 2);

                $psPerformanceTasks = round(($totalPerformanceTasks / $hptTotalGlobal) * 100, 2);
                $wsPerformanceTasks = round($psPerformanceTasks * 0.50, 2);

                $psQuarterlyAssessment = round(($qaScore / $globalHqa) * 100, 2);
                $wsQuarterlyAssessment = round($psQuarterlyAssessment * 0.20, 2);

                return round($wsWrittenWorks + $wsPerformanceTasks + $wsQuarterlyAssessment);
            });

            // Calculate the student's average across all subjects
            $student->average_grade = $grades->isNotEmpty() ? $grades->avg() : null;

            return $student;
        });

        // Filter students
        $highHonors = $students->filter(function ($student) {
            return $student->average_grade >= 90 && $student->average_grade <= 100;
        });

        $needsImprovement = $students->filter(function ($student) {
            return $student->average_grade < 75;
        });

        return view('teacher.reports.students-report', compact('students', 'highHonors', 'needsImprovement'));
    }

    public function downloadGradeReports()
    {
        // Similar logic as gradeReports() but for PDF
        $students = Student::with(['classRecords' => function ($query) {
            $query->with('subject');
        }])->get()->map(function ($student) {
            // Same calculation as above
            $grades = $student->classRecords->map(function ($record) {
                // Your calculation logic for each subject's grade
                $wwScores = json_decode($record->written_works_scores, true) ?? array_fill(0, 10, 0);
                $ptScores = json_decode($record->performance_tasks_scores, true) ?? array_fill(0, 10, 0);
                $qaScore = $record->quarterly_assessment_score ?? 0;

                $hwwArray = json_decode($record->hww_scores, true) ?? array_fill(0, 10, 1);
                $hptArray = json_decode($record->hpt_scores, true) ?? array_fill(0, 10, 1);
                $globalHqa = max($record->global_hqa_score ?? 1, 1);

                $totalWrittenWorks = array_sum($wwScores);
                $totalPerformanceTasks = array_sum($ptScores);
                $hwwTotalGlobal = max(array_sum($hwwArray), 1);
                $hptTotalGlobal = max(array_sum($hptArray), 1);

                $psWrittenWorks = round(($totalWrittenWorks / $hwwTotalGlobal) * 100, 2);
                $wsWrittenWorks = round($psWrittenWorks * 0.30, 2);

                $psPerformanceTasks = round(($totalPerformanceTasks / $hptTotalGlobal) * 100, 2);
                $wsPerformanceTasks = round($psPerformanceTasks * 0.50, 2);

                $psQuarterlyAssessment = round(($qaScore / $globalHqa) * 100, 2);
                $wsQuarterlyAssessment = round($psQuarterlyAssessment * 0.20, 2);

                return round($wsWrittenWorks + $wsPerformanceTasks + $wsQuarterlyAssessment);
            });

            $student->average_grade = $grades->isNotEmpty() ? $grades->avg() : null;
            return $student;
        });

        $highHonors = $students->filter(fn($s) => $s->average_grade >= 90);
        $needsImprovement = $students->filter(fn($s) => $s->average_grade < 75);

        $pdf = \PDF::loadView('teacher.reports.pdf-grade-report', compact('highHonors', 'needsImprovement'));
        return $pdf->download('student-grade-report.pdf');
    }
}
