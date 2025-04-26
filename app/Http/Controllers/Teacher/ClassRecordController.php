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
use App\Models\YearLevel;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class ClassRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query for class records
        $query = ClassRecord::query();

        // Restrict access based on role and school
        if ($user->role === 'teacher') {
            $query->where('user_id', $user->id)
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('school_info_id', $user->school_info_id);
                });
        } elseif ($user->role === 'admin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('school_info_id', $user->school_info_id);
            });
        }

        // Apply search and filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_id', 'like', "%{$search}%")
                    ->orWhere('grade_section', 'like', "%{$search}%")
                    ->orWhere('school_year_id', 'like', "%{$search}%");
            });
        }

        if ($subjectId = $request->input('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        if ($gradeSection = $request->input('grade_section')) {
            $query->where('grade_section', $gradeSection);
        }

        if ($quarter = $request->input('quarter_id')) {
            $query->where('quarter_id', $quarter);
        }

        if ($schoolYear = $request->input('school_year_id')) {
            $query->where('school_year_id', $schoolYear);
        }

        // --- NEW: Prevent duplicate records ---
        // Get unique IDs for each group of fields
        $uniqueIdsQuery = clone $query;
        $uniqueIds = $uniqueIdsQuery
            ->selectRaw('MIN(id) as id') // Use MIN/MAX to pick one ID per group
            ->groupBy('grade_section', 'year_level_id', 'quarter_id', 'school_year_id')
            ->pluck('id')
            ->toArray();

        // Filter main query to include only unique records
        $query->whereIn('id', $uniqueIds);
        // --- END of changes ---

        // Paginate results
        $classRecords = $query->paginate(25);

        // Prepare filter dropdowns
        $subjects = Subject::select('id', 'name')->get();
        $quarters = Quarter::select('id', 'name')->get();
        $schoolYearList = SchoolYear::select('id', 'name')->get();

        // Get grade sections for dropdown
        if ($user->role === 'admin') {
            $gradeSections = ClassRecord::whereHas('user', function ($q) use ($user) {
                $q->where('school_info_id', $user->school_info_id);
            })
                ->select('grade_section', 'user_id')
                ->with('user')
                ->get()
                ->unique('grade_section');
        } else {
            $gradeSections = ClassRecord::where('user_id', $user->id)
                ->select('grade_section', 'user_id')
                ->with('user')
                ->get()
                ->unique('grade_section');
        }

        return view('teacher.class-records.index', [
            'classRecords'  => $classRecords,
            'subjects'      => $subjects,
            'gradeSections' => $gradeSections,
            'quarters'      => $quarters,
            'schoolYear'    => $schoolYearList,
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
        // Validate all required fields.
        $validated = $request->validate([
            'school_year_id'        => 'required|exists:school_years,id',
            'year_level_id'         => 'required|exists:year_levels,id',
            'grade_section'         => 'required|string',
            'teacher'               => 'required|string',
            'quarter_id'            => 'required|exists:quarters,id',
            'subject_id'            => 'required|exists:subjects,id',
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

        // Prevent duplicate ClassRecord
        // Prevent duplicate ClassRecord
        $schoolYearId = $validated['school_year_id']; // Directly use validated school_year_id

        $exists = ClassRecord::where('quarter_id', $validated['quarter_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('grade_section', $validated['grade_section'])
            ->where('user_id', auth()->id())
            ->where('school_year_id', $schoolYearId)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'A class record already exists for this subject, quarter, and grade section.');
        }

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
                'year_level_id'             => $validated['year_level_id'],
                'school_year_id'            => $validated['school_year_id'],
                'user_id'                   => $validated['user_id'],
                'subject_id'                => $validated['subject_id'],
                'grade_section'             => $validated['grade_section'],
                'quarter_id'                => $validated['quarter_id'],
                'school_year_id'               => $validated['school_year_id'],
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

            // Create the ClassRecord.
            ClassRecord::create($data);
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

    // public function edit(Request $request, ClassRecord $classRecord)
    // {
    //     // Restrict access to only teachers
    //     if (auth()->user()->role !== 'teacher') {
    //         abort(403, 'Unauthorized action. Only teachers can edit class records.');
    //     }

    //     // Get the selected school year id from the query string.
    //     $selectedYear = $request->input('school_year_id');

    //     // If a school year is selected, filter students using school_year_id.
    //     $students = [];
    //     if ($selectedYear) {
    //         $students = Student::where('school_year_id', $selectedYear)
    //             ->where('user_id', auth()->user()->id)
    //             ->get();
    //     }

    //     $schoolYear = SchoolYear::all();
    //     $subject    = Subject::all();
    //     $schoolInfo = SchoolInfo::all();
    //     $quarter    = Quarter::all();
    //     $newStudents = Student::all();

    //     // Retrieve all rows (per student) that match the current record's old group fields:
    //     // (subject_id, quarter, grade_section, school_year).
    //     $groupRecords = ClassRecord::where('subject_id', $classRecord->subject_id)
    //         ->where('quarter_id', $classRecord->quarter_id)
    //         ->where('grade_section', $classRecord->grade_section)
    //         ->where('school_year_id', $classRecord->school_year_id)
    //         ->get();

    //     return view('teacher.class-records.edit', compact(
    //         'classRecord',
    //         'groupRecords',
    //         'students',
    //         'newStudents',
    //         'schoolYear',
    //         'subject',
    //         'schoolInfo',
    //         'quarter'
    //     ));
    // }
    public function edit(Request $request, ClassRecord $classRecord)
    {
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Unauthorized action. Only teachers can edit class records.');
        }

        // Use the classRecord's school_year_id as default if not provided
        $selectedYear = $request->input('school_year_id', $classRecord->school_year_id);

        // Fetch students under the selected school year and teacher
        $students = Student::where('school_year_id', $selectedYear)
            ->where('user_id', auth()->user()->id)
            ->get();

        // Fetch existing class records for the group (using selectedYear instead of the original)
        $groupRecords = ClassRecord::where('subject_id', $classRecord->subject_id)
            ->where('quarter_id', $classRecord->quarter_id)
            ->where('grade_section', $classRecord->grade_section)
            ->where('school_year_id', $selectedYear) // Use selectedYear instead of original
            ->get();

        // Identify students NOT already in the class records
        $existingStudentIds = $groupRecords->pluck('student_id')->toArray();
        $newStudents = $students->whereNotIn('id', $existingStudentIds);

        // Merge existing records with new students (as "draft" records)
        $mergedRecords = $groupRecords->merge(
            $newStudents->map(function ($student) use ($classRecord) {
                return new ClassRecord([
                    'student_id' => $student->id,
                    'subject_id' => $classRecord->subject_id,
                    'quarter_id' => $classRecord->quarter_id,
                    'grade_section' => $classRecord->grade_section,
                    'school_year_id' => $classRecord->school_year_id,
                    // Add other default fields here
                ]);
            })
        );

        $schoolYear = SchoolYear::all();
        $subject = Subject::all();
        $schoolInfo = SchoolInfo::all();
        $quarter = Quarter::all();

        return view('teacher.class-records.edit', compact(
            'classRecord',
            'mergedRecords', // Pass merged records to the view
            'students',
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
        // dd($request->all());
        // 1) Validation rules.
        $rules = [
            'year_level_id' => 'required|exists:year_levels,id',
            'subject_id'       => 'required|exists:subjects,id',
            'grade_section'    => 'required|string',
            'quarter_id'       => 'required|exists:quarters,id',
            'school_year_id'   => 'required|exists:school_years,id',
            'teacher'          => 'required|string',
            'hww'              => 'required|array|size:10',
            'hww.*'            => 'nullable|numeric|min:0',
            'hpt'              => 'required|array|size:10',
            'hpt.*'            => 'nullable|numeric|min:0',
            'global_hqa'       => 'required|numeric|min:0',
            'student_id'       => 'required|array',
            'student_id.*'     => 'exists:students,id',
            'written_works'    => 'required|array',
            'performance_tasks' => 'required|array',
            'quarterly_assessment' => 'required|array',
        ];

        $validated = $request->validate($rules);

        // Use authenticated user's ID instead of request input
        $userId = auth()->id();

        // Update group records with AUTHENTICATED USER ID
        ClassRecord::where('subject_id', $classRecord->subject_id)
            ->where('quarter_id', $classRecord->quarter_id)
            ->where('grade_section', $classRecord->grade_section)
            ->where('school_year_id', $classRecord->school_year_id)
            ->update([
                'user_id'         => $userId, // From auth()->id()
                'subject_id'      => $validated['subject_id'], // Corrected field
                'grade_section'   => $validated['grade_section'],
                'quarter_id'      => $validated['quarter_id'],
                'school_year_id' => $validated['school_year_id'],
                'year_level_id' => $validated['year_level_id'],
                'teacher'         => $validated['teacher'],
                'hww'            => $validated['hww'],
                'hpt'            => $validated['hpt'],
                'global_hqa'      => floatval($validated['global_hqa']),
            ]);

        // 4) Compute global totals from the new header arrays.
        $hwwArray       = array_values($validated['hww']);
        $hptArray       = array_values($validated['hpt']);
        $hwwTotalGlobal = max(array_sum(array_map('floatval', $hwwArray)), 1);
        $hptTotalGlobal = max(array_sum(array_map('floatval', $hptArray)), 1);
        $globalHqa      = max(floatval($validated['global_hqa']), 1);

        // 5) Now retrieve the UPDATED rows using the NEW group fields from $validated.
        $newSubject   = $validated['subject_id'];
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


            $quarterlyGrade = $this->getTransmutedGrade($initialGrade);

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
                    'student_id'        => $studentId,
                    'user_id'           => Auth::id(),
                    'subject_id'        => $newSubject,
                    'grade_section'     => $newGradeSec,
                    'quarter_id'        => $newQuarter,
                    'school_year_id'    => $newSchoolYear,
                    'year_level_id'     => $validated['year_level_id'],
                    'teacher'           => $validated['teacher'],
                    'hww'               => $hwwArray,
                    'hpt'               => $hptArray,
                    'global_hqa'        => $globalHqa,
                ];
                $newData = array_merge($newData, $detailData);

                $classRecord = ClassRecord::create($newData);

                // Log the action for the newly created record
                // Fixed activity log (auth()->user()->name instead of auth()->id()->name)
                ActivityLogService::log(
                    'Updated Class Record',
                    "User: " . auth()->user()->name . " updated class record for " .
                        "Subject ID: {$validated['subject_id']}, " .
                        "Grade Section: {$validated['grade_section']}"
                );
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

    private function getTransmutedGrade($initialGrade)
            {
                $transmutationTable = [
                    ['min' => 100,    'grade' => 100],
                    ['min' => 98.40,  'grade' => 99],
                    ['min' => 96.80,  'grade' => 98],
                    ['min' => 95.20,  'grade' => 97],
                    ['min' => 93.60,  'grade' => 96],
                    ['min' => 92.00,  'grade' => 95],
                    ['min' => 90.40,  'grade' => 94],
                    ['min' => 88.80,  'grade' => 93],
                    ['min' => 87.20,  'grade' => 92],
                    ['min' => 85.60,  'grade' => 91],
                    ['min' => 84.00,  'grade' => 90],
                    ['min' => 82.40,  'grade' => 89],
                    ['min' => 80.80,  'grade' => 88],
                    ['min' => 79.20,  'grade' => 87],
                    ['min' => 77.60,  'grade' => 86],
                    ['min' => 76.00,  'grade' => 85],
                    ['min' => 74.40,  'grade' => 84],
                    ['min' => 72.80,  'grade' => 83],
                    ['min' => 71.20,  'grade' => 82],
                    ['min' => 69.60,  'grade' => 81],
                    ['min' => 68.00,  'grade' => 80],
                    ['min' => 66.40,  'grade' => 79],
                    ['min' => 64.80,  'grade' => 78],
                    ['min' => 63.20,  'grade' => 77],
                    ['min' => 61.60,  'grade' => 76],
                    ['min' => 60.00,  'grade' => 60],
                    ['min' => 56.00,  'grade' => 74],
                    ['min' => 52.00,  'grade' => 73],
                    ['min' => 48.00,  'grade' => 72],
                    ['min' => 44.00,  'grade' => 71],
                    ['min' => 40.00,  'grade' => 70],
                    ['min' => 36.00,  'grade' => 69],
                    ['min' => 32.00,  'grade' => 68],
                    ['min' => 28.00,  'grade' => 67],
                    ['min' => 24.00,  'grade' => 66],
                    ['min' => 20.00,  'grade' => 65],
                    ['min' => 16.00,  'grade' => 64],
                    ['min' => 12.00,  'grade' => 63],
                    ['min' => 8.00,   'grade' => 62],
                    ['min' => 4.00,   'grade' => 61],
                    ['min' => 0,      'grade' => 60]
                ];

                // Sort descendingly by 'min'
                usort($transmutationTable, function ($a, $b) {
                    return $b['min'] <=> $a['min'];
                });

                foreach ($transmutationTable as $entry) {
                    if ($initialGrade >= $entry['min']) {
                        return $entry['grade'];
                    }
                }
                return 60; // Default
            }
}
