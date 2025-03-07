<?php

namespace App\Http\Controllers\Teacher;

use App\Models\ClassRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolInfo;
use App\Models\Subject;
use App\Models\User;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Quarter;

class ClassRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRecord::query();

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                    ->orWhere('grade_section', 'like', "%{$search}%")
                    ->orWhere('school_year', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($subject = $request->input('subject_name')) {
            $query->where('subject_name', $subject);
        }
        if ($grade = $request->input('grade_section')) {
            $query->where('grade_section', $grade);
        }
        if ($quarter = $request->input('quarter')) {
            $query->where('quarter', $quarter);
        }

        $classRecords = $query->get();

        // Get unique values for filters
        $subjects = Subject::distinct()->pluck('name');
        $yearLevel = User::distinct()->pluck('year_level_id');
        $quarters = range(1, 4);

        return view('teacher.class-records.index', compact('classRecords', 'subjects', 'yearLevel', 'quarters'));
    }

    public function create()
    {
        $user = User::all();
        $students = Student::where('user_id', auth()->user()->id)->get();
        $schoolYear = SchoolYear::all();
        $subject = Subject::all();
        $schoolInfo = SchoolInfo::all();
        $quarter = Quarter::all();

        return view('teacher.class-records.create', compact('user', 'students', 'schoolYear', 'subject', 'schoolInfo', 'quarter'));
    }

    public function store(Request $request)
    {
        // Remove or comment out dd() so processing continues
        // dd($request->all());

        // Validate all required fields.
        $validated = $request->validate([
            'school_year'           => 'required|string',
            'quarter'               => 'required|string',
            'grade_section'         => 'required|string',
            'teacher'               => 'required|string',
            'subject'               => 'required|string',
            'hww'                   => 'required|array|size:10',
            'hww.*'                 => 'nullable|numeric|min:0',
            'hpt'                   => 'required|array|size:10',
            'hpt.*'                 => 'nullable|numeric|min:0',
            'global_hqa'            => 'required|numeric|min:0',
            'student_id'            => 'required|array',
            'student_id.*'          => 'exists:students,id',
            'user_id'               => 'required|exists:users,id',
            // Written works, performance tasks, and quarterly assessment per student:
            'written_works'         => 'required|array',
            'performance_tasks'     => 'required|array',
            'quarterly_assessment'  => 'required|array',
        ]);


        // Global header scores.
        $hwwArray = array_values($validated['hww']);
        $hptArray = array_values($validated['hpt']);
        $globalHqa = max(floatval($validated['global_hqa']), 1);

        // Compute global totals.
        $hwwTotalGlobal = max(array_sum(array_map('floatval', $hwwArray)), 1);
        $hptTotalGlobal = max(array_sum(array_map('floatval', $hptArray)), 1);

        // Loop over each student.
        foreach ($validated['student_id'] as $studentId) {

            // Retrieve written works scores for this student.
            $wwScores = isset($validated['written_works'][$studentId])
                ? array_map('floatval', $validated['written_works'][$studentId])
                : array_fill(0, 10, 0);
            if (count($wwScores) < 10) {
                $wwScores = array_pad($wwScores, 10, 0);
            }

            // Retrieve performance tasks scores for this student.
            $ptScores = isset($validated['performance_tasks'][$studentId])
                ? array_map('floatval', $validated['performance_tasks'][$studentId])
                : array_fill(0, 10, 0);
            if (count($ptScores) < 10) {
                $ptScores = array_pad($ptScores, 10, 0);
            }

            // Get the student's quarterly assessment score.
            $studentQuarterlyAssessment = isset($validated['quarterly_assessment'][$studentId])
                ? floatval($validated['quarterly_assessment'][$studentId])
                : 0;

            // Calculate totals.
            $totalWrittenWorks = array_sum($wwScores);
            $totalPerformanceTasks = array_sum($ptScores);

            // Calculate percentages and weighted scores.
            $psWrittenWorks = round(($totalWrittenWorks / $hwwTotalGlobal) * 100, 2);
            $wsWrittenWorks = round($psWrittenWorks * 0.30, 2);

            $psPerformanceTasks = round(($totalPerformanceTasks / $hptTotalGlobal) * 100, 2);
            $wsPerformanceTasks = round($psPerformanceTasks * 0.50, 2);

            $psQuarterlyAssessment = round(($studentQuarterlyAssessment / $globalHqa) * 100, 2);
            $wsQuarterlyAssessment = round($psQuarterlyAssessment * 0.20, 2);

            // Compute final grades.
            $initialGrade = round($wsWrittenWorks + $wsPerformanceTasks + $wsQuarterlyAssessment, 2);
            $quarterlyGrade = round($initialGrade);

            // Build data array.
            $data = [
                'student_id'                => $studentId,
                'user_id'                   => $validated['user_id'],
                'subject_id'                => $validated['subject'],
                'grade_section'             => $validated['grade_section'],
                'quarter'                   => $validated['quarter'],
                'school_year'               => $validated['school_year'],
                'teacher'                   => $validated['teacher'],
                'written_works_total'       => $totalWrittenWorks,
                'written_works_ps'          => $psWrittenWorks,
                'written_works_ws'          => $wsWrittenWorks,
                'performance_tasks_total'   => $totalPerformanceTasks,
                'performance_tasks_ps'      => $psPerformanceTasks,
                'performance_tasks_ws'      => $wsPerformanceTasks,
                'quarterly_assessment'      => $studentQuarterlyAssessment,
                'quarterly_assessment_ps'   => $psQuarterlyAssessment,
                'quarterly_assessment_ws'   => $wsQuarterlyAssessment,
                'initial_grade'             => $initialGrade,
                'quarterly_grade'           => $quarterlyGrade,
            ];

            // Add individual scores.
            for ($i = 1; $i <= 10; $i++) {
                $data["written_work_{$i}"] = $wwScores[$i - 1];
                $data["performance_task_{$i}"] = $ptScores[$i - 1];
            }

            // Create the ClassRecord.
            ClassRecord::create($data);
        }

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'Class record created successfully.');
    }



    public function edit(ClassRecord $classRecord)
    {
        return view('class-records.edit', compact('classRecord'));
    }

    public function update(Request $request, ClassRecord $classRecord)
    {
        $validated = $request->validate(ClassRecord::rules());

        $classRecord->update($validated);

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'Class record updated successfully.');
    }

    public function destroy(ClassRecord $classRecord)
    {
        $classRecord->delete();

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'Class record deleted successfully.');
    }
}
