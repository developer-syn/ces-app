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
use App\Services\ActivityLogService;

class ClassRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query for class records
        $query = ClassRecord::query();

        // Filter records based on the user's role
        if ($user->role === 'teacher') {
            // Teachers can only view their own records
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            // Admin can view all records (no additional filtering needed)
        }

        // 1) Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_id', 'like', "%{$search}%")
                    ->orWhere('grade_section', 'like', "%{$search}%")
                    ->orWhere('school_year_id', 'like', "%{$search}%");
            });
        }

        // 2) Subject filter
        if ($subjectId = $request->input('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        // 3) Grade & Section filter
        if ($gradeSection = $request->input('grade_section')) {
            $query->where('grade_section', $gradeSection);
        }

        // 4) Quarter filter
        if ($quarter = $request->input('quarter_id')) {
            $query->where('quarter_id', $quarter);
        }
        // 5) schoo year filter
        if ($schoolYear = $request->input('school_year_id')) {
            $query->where('school_year_id', $schoolYear);
        }

        // Finally, get the records
        $classRecords = $query->get();

        // For the subject filter dropdown
        $subjects = Subject::select('id', 'name')->get();

        // For the grade & section filter, get distinct grade_section values
        $gradeSections = ClassRecord::distinct()->pluck('grade_section');

        // For quarter, if you store "First Quarter", etc.
        $quarters = Quarter::select('id', 'name')->get();

        $schoolYear = SchoolYear::select('id', 'name')->get();

        return view('teacher.class-records.index', [
            'classRecords'  => $classRecords,
            'subjects'      => $subjects,
            'gradeSections' => $gradeSections,
            'quarters'      => $quarters,
            'schoolYear'    => $schoolYear,
        ]);
    }



    public function create(Request $request)
    {
        // Restrict access to only teachers
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Unauthorized action. Only teachers can create class records.');
        }

        // Retrieve all school years for the dropdown.
        $allSchoolYears = SchoolYear::all();

        // Get the selected school year id from the query string.
        $selectedYear = $request->input('school_year'); // This will now be the school year's id

        // If a school year is selected, filter students using school_year_id.
        $students = [];
        if ($selectedYear) {
            $students = Student::where('school_year_id', $selectedYear)
                ->where('user_id', auth()->user()->id)
                ->get();
        }

        $user = User::all();
        $subject = Subject::all();
        $schoolInfo = SchoolInfo::all();
        $quarter = Quarter::all();

        return view('teacher.class-records.create', [
            'schoolYear'   => $allSchoolYears,
            'selectedYear' => $selectedYear,
            'students'     => $students,
            'user'         => $user,
            'subject'      => $subject,
            'schoolInfo'   => $schoolInfo,
            'quarter'      => $quarter,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate all required fields.
        $validated = $request->validate([
            'quarter_id'            => 'required|exists:quarters,id',
            'grade_section'         => 'required|string',
            'teacher'               => 'required|string',
            'subject_id'            => 'required|exists:subjects,id',
            'hww'                   => 'required|array|size:10',
            'hww.*'                 => 'nullable|numeric|min:0',
            'hpt'                   => 'required|array|size:10',
            'hpt.*'                 => 'nullable|numeric|min:0',
            'global_hqa'            => 'required|numeric|min:0',
            'student_id'            => 'required|array',
            'student_id.*'          => 'exists:students,id',
            'user_id'               => 'required|exists:users,id',
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
            // Retrieve the student to get the year level.
            $student = Student::findOrFail($studentId);

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
                'subject_id'                => $validated['subject_id'],
                'grade_section'             => $validated['grade_section'],
                'quarter_id'                => $validated['quarter_id'],
                'year_level_id'             => $student->year_level_id, // Use the student's year level
                'school_year_id'            => $student->school_year_id, // Use the student's
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
                'hww'                       => $hwwArray,   // or json_encode($hwwArray)
                'hpt'                       => $hptArray,   // or json_encode($hptArray)
                'global_hqa'                => $globalHqa,
            ];

            // Add individual scores.
            for ($i = 1; $i <= 10; $i++) {
                $data["written_work_{$i}"] = $wwScores[$i - 1];
                $data["performance_task_{$i}"] = $ptScores[$i - 1];
            }

            // Create the ClassRecord and store the instance.
            $classRecord = ClassRecord::create($data);

            // ✅ Log the action
            ActivityLogService::log('Added Class Record', "User: " . auth()->user()->name . " - created class record for subject: {$classRecord->subject->name}");
        }

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'Class record created successfully.');
    }

    public function showGroup(Request $request)
    {
        // e.g., subject_id, grade_section, school_year
        $subjectId     = $request->get('subject_id');
        $gradeSection  = $request->get('grade_section');
        $schoolYearId  = $request->get('school_year_id');

        // Fetch all records that match the group
        $records = ClassRecord::where('subject_id', $subjectId)
            ->where('grade_section', $gradeSection)
            ->where('school_year_id', $schoolYearId)
            ->where('user_id', auth()->id())
            ->with(['subject', 'schoolYear'])
            ->get();

        // Pass them to a 'show-group' Blade view
        return view('teacher.class-records.show-group', compact('records'));
    }



    public function edit(Request $request, ClassRecord $classRecord)
    {
        // Restrict access to only teachers
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Unauthorized action. Only teachers can edit class records.');
        }

        // Get the selected school year id from the query string.
        $selectedYear = $request->input('school_year_id'); // This will now be the school year's id

        // If a school year is selected, filter students using school_year_id.
        $students = [];
        if ($selectedYear) {
            $students = Student::where('school_year_id', $selectedYear)
                ->where('user_id', auth()->user()->id)
                ->get();
        }

        $schoolYear = SchoolYear::all();
        $subject    = Subject::all();
        $schoolInfo = SchoolInfo::all();
        $quarter    = Quarter::all();
        $newStudents = Student::all();

        // Retrieve all rows (per student) that match the current record's old group fields:
        // (subject_id, quarter, grade_section, school_year).
        $groupRecords = ClassRecord::where('subject_id', $classRecord->subject_id)
            ->where('quarter_id', $classRecord->quarter_id)
            ->where('grade_section', $classRecord->grade_section)
            ->where('school_year_id', $classRecord->school_year_id)
            ->get();

        return view('teacher.class-records.edit', compact(
            'classRecord',
            'groupRecords',
            'students',
            'newStudents',
            'schoolYear',
            'subject',
            'schoolInfo',
            'quarter'
        ));
    }

    /**
     * Update the group of class records.
     */
    public function update(Request $request, ClassRecord $classRecord)
    {
        // 1) Validation rules.
        $rules = [
            'user_id'         => 'required|exists:users,id',
            'subject'         => 'required|string',
            'grade_section'   => 'required|string',
            'quarter_id'      => 'required|exists:quarters,id',
            'school_year_id'  => 'required|exists:school_years,id',
            'teacher'         => 'required|string',
            'hww'             => 'required|array|size:10',
            'hww.*'           => 'nullable|numeric|min:0',
            'hpt'             => 'required|array|size:10',
            'hpt.*'           => 'nullable|numeric|min:0',
            'global_hqa'      => 'required|numeric|min:0',

            // Detail fields:
            'student_id'            => 'required|array',
            'student_id.*'          => 'exists:students,id',
            'written_works'         => 'required|array',     // keys = student IDs
            'performance_tasks'     => 'required|array',     // keys = student IDs
            'quarterly_assessment'  => 'required|array',     // keys = student IDs
        ];

        $validated = $request->validate($rules);

        // 2) Store the old group fields (the ones used to locate existing rows).
        $oldSubject   = $classRecord->subject_id;
        $oldQuarter   = $classRecord->quarter_id;
        $oldGradeSec  = $classRecord->grade_section;
        $oldSchoolYear = $classRecord->school_year_id;

        // 3) Update all rows in the OLD group with new header values.
        ClassRecord::where('subject_id', $oldSubject)
            ->where('quarter_id', $oldQuarter)
            ->where('grade_section', $oldGradeSec)
            ->where('school_year_id', $oldSchoolYear)
            ->update([
                'user_id'       => $validated['user_id'],
                'subject_id'    => $validated['subject'],
                'grade_section' => $validated['grade_section'],
                'quarter_id'    => $validated['quarter_id'],
                'school_year_id'   => $validated['school_year_id'],
                'teacher'       => $validated['teacher'],
                'hww'           => $validated['hww'],
                'hpt'           => $validated['hpt'],
                'global_hqa'    => floatval($validated['global_hqa']),
            ]);

        // 4) Compute global totals from the new header arrays.
        $hwwArray       = array_values($validated['hww']);
        $hptArray       = array_values($validated['hpt']);
        $globalHqa      = max(floatval($validated['global_hqa']), 1);
        $hwwTotalGlobal = max(array_sum(array_map('floatval', $hwwArray)), 1);
        $hptTotalGlobal = max(array_sum(array_map('floatval', $hptArray)), 1);

        // 5) Now retrieve the UPDATED rows using the NEW group fields from $validated.
        $newSubject   = $validated['subject'];
        $newQuarter   = $validated['quarter_id'];
        $newGradeSec  = $validated['grade_section'];
        $newSchoolYear = $validated['school_year_id'];

        $groupRecords = ClassRecord::where('subject_id', $newSubject)
            ->where('quarter_id', $newQuarter)
            ->where('grade_section', $newGradeSec)
            ->where('school_year_id', $newSchoolYear)
            ->get();

        // 6) Identify students to be removed.
        $existingStudentIds = $groupRecords->pluck('student_id')->toArray();
        $updatedStudentIds = $validated['student_id'];

        // Find students to remove (those in the database but not in the updated list).
        $studentsToRemove = array_diff($existingStudentIds, $updatedStudentIds);

        // Remove the students from the class record.
        ClassRecord::where('subject_id', $newSubject)
            ->where('quarter_id', $newQuarter)
            ->where('grade_section', $newGradeSec)
            ->where('school_year_id', $newSchoolYear)
            ->whereIn('student_id', $studentsToRemove)
            ->delete();

        // 7) Loop over each student. Update or create rows in the updated group.
        foreach ($validated['student_id'] as $studentId) {
            // Written works.
            $wwScores = isset($validated['written_works'][$studentId])
                ? array_map('floatval', $validated['written_works'][$studentId])
                : array_fill(0, 10, 0);
            if (count($wwScores) < 10) {
                $wwScores = array_pad($wwScores, 10, 0);
            }

            // Performance tasks.
            $ptScores = isset($validated['performance_tasks'][$studentId])
                ? array_map('floatval', $validated['performance_tasks'][$studentId])
                : array_fill(0, 10, 0);
            if (count($ptScores) < 10) {
                $ptScores = array_pad($ptScores, 10, 0);
            }

            // Quarterly assessment.
            $studentQA = isset($validated['quarterly_assessment'][$studentId])
                ? floatval($validated['quarterly_assessment'][$studentId])
                : 0;

            // Calculate totals and weighted scores.
            $totalWrittenWorks = array_sum($wwScores);
            $psWrittenWorks    = round(($totalWrittenWorks / $hwwTotalGlobal) * 100, 2);
            $wsWrittenWorks    = round($psWrittenWorks * 0.30, 2);

            $totalPerformanceTasks = array_sum($ptScores);
            $psPerformanceTasks    = round(($totalPerformanceTasks / $hptTotalGlobal) * 100, 2);
            $wsPerformanceTasks    = round($psPerformanceTasks * 0.50, 2);

            $psQA = round(($studentQA / $globalHqa) * 100, 2);
            $wsQA = round($psQA * 0.20, 2);

            $initialGrade   = round($wsWrittenWorks + $wsPerformanceTasks + $wsQA, 2);
            $quarterlyGrade = round($initialGrade);

            // Build detail data for this student.
            $detailData = [
                'written_works_total'     => $totalWrittenWorks,
                'written_works_ps'        => $psWrittenWorks,
                'written_works_ws'        => $wsWrittenWorks,
                'performance_tasks_total' => $totalPerformanceTasks,
                'performance_tasks_ps'    => $psPerformanceTasks,
                'performance_tasks_ws'    => $wsPerformanceTasks,
                'quarterly_assessment'    => $studentQA,
                'quarterly_assessment_ps' => $psQA,
                'quarterly_assessment_ws' => $wsQA,
                'initial_grade'           => $initialGrade,
                'quarterly_grade'         => $quarterlyGrade,
                // Also update the global arrays in each row if needed:
                'hww'                     => $hwwArray,
                'hpt'                     => $hptArray,
                'global_hqa'              => $globalHqa,
            ];

            // Add individual scores (written_work_1..10, performance_task_1..10).
            for ($i = 1; $i <= 10; $i++) {
                $detailData["written_work_$i"]       = $wwScores[$i - 1];
                $detailData["performance_task_$i"]   = $ptScores[$i - 1];
            }

            // Try to find an existing record for this student in the updated group.
            $detail = $groupRecords->firstWhere('student_id', $studentId);
            if ($detail) {
                // Update existing detail.
                $detail->update($detailData);
            } else {
                // If no record exists for this student, create a new one in the updated group.
                $newData = [
                    'student_id'    => $studentId,
                    'user_id'       => $validated['user_id'],
                    'subject_id'    => $newSubject,
                    'grade_section' => $newGradeSec,
                    'quarter_id'    => $newQuarter,
                    'school_year_id'   => $newSchoolYear,
                    'teacher'       => $validated['teacher'],
                    'hww'           => $hwwArray,
                    'hpt'           => $hptArray,
                    'global_hqa'    => $globalHqa,
                ];
                $newData = array_merge($newData, $detailData);

                $classRecord = ClassRecord::create($newData);

                // Log the action for the newly created record
                ActivityLogService::log('Updated Class Record', "User: " . auth()->id()->name . " - updated or added class record for subject ID: {$classRecord->newSubject}, grade section: {$classRecord->newGradeSec}, quarter ID: {$classRecord->newQuarter}, school year ID: {$classRecord->newSchoolYear}");
            }
        }

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'Class record updated successfully.');
    }


    public function destroy(ClassRecord $classRecord)
    {
        // Identify the grouping fields from the single record
        $subjectId   = $classRecord->subject_id;
        $quarter     = $classRecord->quarter_id;
        $gradeSection = $classRecord->grade_section;
        $schoolYear  = $classRecord->school_year_id;

        // Delete all rows that match this group
        ClassRecord::where('subject_id', $subjectId)
            ->where('quarter_id', $quarter)
            ->where('grade_section', $gradeSection)
            ->where('school_year_id', $schoolYear)
            ->delete();

        return redirect()
            ->route('teacher.class-records.index')
            ->with('success', 'All records for this class quarter were deleted successfully.');
    }
}
