<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Quarter;
use App\Http\Controllers\Controller;
use App\Models\ClassRecord;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $schoolInfo = SchoolInfo::get()->first();
        $grades = [];

        // Fetch class records for the current year level and school year
        $classRecords = ClassRecord::where('student_id', $id)
            ->where('year_level_id', $student->year_level_id)
            ->where('school_year_id', $student->school_year_id)
            ->with(['subject', 'quarter'])
            ->get();

        // Group records by subject and quarter
        foreach ($classRecords as $record) {
            $subject = strtolower($record->subject->name);
            $quarter = $record->quarter_id;
            $grades[$subject][$quarter] = $record->quarterly_grade;
        }

        // Calculate final ratings and remarks for each subject
        foreach ($grades as $subject => &$quarters) {
            $quarterGrades = array_filter($quarters, 'is_numeric');
            if (!empty($quarterGrades)) {
                $final = round(array_sum($quarterGrades) / count($quarterGrades));
                $quarters['final'] = $final;
                $quarters['remarks'] = $final >= 75 ? 'Passed' : 'Failed';
            }
        }

        // Calculate MAPEH average
        if (isset($grades['music']) && isset($grades['art']) && isset($grades['pe']) && isset($grades['health'])) {
            $mapehGrades = [
                'music' => $grades['music']['final'] ?? null,
                'art' => $grades['art']['final'] ?? null,
                'pe' => $grades['pe']['final'] ?? null,
                'health' => $grades['health']['final'] ?? null
            ];

            $validGrades = array_filter($mapehGrades, 'is_numeric');
            if (!empty($validGrades)) {
                $mapehFinal = round(array_sum($validGrades) / count($validGrades));
                $grades['mapeh']['final'] = $mapehFinal;
                $grades['mapeh']['remarks'] = $mapehFinal >= 75 ? 'Passed' : 'Failed';
            }
        }

        // Calculate General Average
        $finalGrades = [];
        foreach ($grades as $subject => $data) {
            if ($subject !== 'music' && $subject !== 'art' && $subject !== 'pe' && $subject !== 'health') {
                if (isset($data['final'])) {
                    $finalGrades[] = $data['final'];
                }
            }
        }

        $generalAverage = !empty($finalGrades) ? round(array_sum($finalGrades) / count($finalGrades)) : null;

        return view('teacher.school-forms-10.sf10', compact('student', 'schoolInfo', 'grades', 'generalAverage'));
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
