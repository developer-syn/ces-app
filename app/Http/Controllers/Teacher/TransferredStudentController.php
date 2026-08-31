<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransferredStudent;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\SchoolInfo;
use App\Models\User;

class TransferredStudentController extends Controller
{
    // public function index()
    // {
    //     $transferredStudents = TransferredStudent::with(['student', 'yearLevel', 'schoolYear', 'subject', 'schoolInfo'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('teacher.transfer-students.index', compact('transferredStudents'));
    // }
    /**
     * Show the form to add grades for a transferred student.
     */
    public function create(Request $request)
    {
        $students = Student::where('status','transferred')->get();
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();
        $subjects = Subject::all();
        $schoolInfos = SchoolInfo::all();
        $users = User::all();

        return view('teacher.transfer-students.create', compact(
            'students',
            'yearLevels',
            'schoolYears',
            'subjects',
            'schoolInfos',
            'users'
        ));
    }

    /**
     * Store a new transferred student grade record.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'student_id'      => 'required|exists:students,id',
        'year_level_id'   => 'required|exists:year_levels,id',
        'school_year_id'  => 'required|exists:school_years,id',
        'school_info_id'  => 'nullable|exists:school_infos,id',
        'section'         => 'nullable|string|max:255',
        'user_id'         => 'required|exists:users,id',
        'subjects'        => 'required|array|min:1',
        'subjects.*.subject_id' => 'required|exists:subjects,id',
        'subjects.*.q1'   => 'nullable|numeric|min:60|max:100',
        'subjects.*.q2'   => 'nullable|numeric|min:60|max:100',
        'subjects.*.q3'   => 'nullable|numeric|min:60|max:100',
        'subjects.*.q4'   => 'nullable|numeric|min:60|max:100',
    ]);

    foreach ($request->subjects as $subject) {
        // Calculate final average and transmuted grade
        $grades = array_filter([
            $subject['q1'],
            $subject['q2'],
            $subject['q3'],
            $subject['q4']
        ], fn($grade) => $grade !== null);

        $rawAverage = count($grades) > 0 ? array_sum($grades) / count($grades) : null;
        $remarks = $rawAverage !== null ? ($rawAverage >= 75 ? 'Passed' : 'Failed') : null;

        TransferredStudent::create([
            'student_id'      => $request->student_id,
            'subject_id'      => $subject['subject_id'],
            'q1_grade'        => $subject['q1'] ?? null,
            'q2_grade'        => $subject['q2'] ?? null,
            'q3_grade'        => $subject['q3'] ?? null,
            'q4_grade'        => $subject['q4'] ?? null,
            'final_rating'    => $rawAverage,
            'remarks'         => $remarks,
            'school_year_id'  => $request->school_year_id,
            'year_level_id'   => $request->year_level_id,
            'section'         => $request->section,
            'user_id'         => $request->user_id,
            'school_info_id'  => $request->school_info_id,
        ]);
    }

    return redirect()
        ->route('teacher.students.index')
        ->with('success', 'Transferred student grades recorded successfully.');
}


    /**
     * Display a specific transferred student record.
     */
    public function show($id)
    {
        $record = TransferredStudent::with(['student', 'yearLevel', 'schoolYear', 'subject', 'schoolInfo'])->findOrFail($id);

        return response()->json($record);
    }

    /**
     * Update an existing transferred student record.
     */
    public function update(Request $request, $id)
    {
        $transferredStudent = TransferredStudent::findOrFail($id);

        $validated = $request->validate([
            'section'         => 'nullable|string|max:255',
            'school_info_id'  => 'nullable|exists:school_infos,id',
            'q1_grade'        => 'nullable|numeric|min:60|max:100',
            'q2_grade'        => 'nullable|numeric|min:60|max:100',
            'q3_grade'        => 'nullable|numeric|min:60|max:100',
            'q4_grade'        => 'nullable|numeric|min:60|max:100',
        ]);

        $transferredStudent->update($validated);

        return response()->json([
            'message' => 'Transferred student record updated successfully.',
            'data' => $transferredStudent
        ]);
    }

    /**
     * Remove a transferred student record.
     */
    public function destroy($id)
    {
        $record = TransferredStudent::findOrFail($id);
        $record->delete();

        return response()->json([
            'message' => 'Transferred student record deleted.'
        ]);
    }

}
