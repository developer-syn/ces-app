<?php

namespace App\Http\Controllers\Teacher;

use App\Models\AttendanceCoreValue;
use App\Models\StudentEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceCoreValuesController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, StudentEnrollment $enrollment): View|RedirectResponse
    {
        // Get the teacher's assigned year level(s)
        $teacherYearLevel = auth()->user()->year_level_id;
        // OR for multiple assignments:
        // $teacherYearLevels = auth()->user()->yearLevels()->pluck('id');

        // Check authorization
        if ($enrollment->year_level_id !== $teacherYearLevel) {
            abort(403, 'You are not authorized to access this year level');
        }

        // 🧑‍🏫 If user is a teacher, only show their enrolled students
        // 🧑‍💼 If user is admin, restrict to students in the same school_info_id
        // Optional: Filter by specific teacher (only within their school)
        $user = Auth::user();
        $query = Student::with(['enrollments.yearLevel', 'enrollments.schoolYear', 'enrollments.teacher']);

        // 🧑‍🏫 If user is a teacher, only show their enrolled students
        if ($user->role === 'teacher') {
            $query->whereHas('enrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->role === 'admin') { // 🧑‍💼 If user is admin, restrict to students in the same school_info_id
            $query->where('school_info_id', $user->school_info_id);

            // Optional: Filter by specific teacher (only within their school)
            if ($request->filled('user_id')) {
                $query->whereHas('enrollments', function ($q) use ($request) {
                    $q->where('user_id', $request->input('user_id'));
                });
            }
        }

        if ($enrollment->attendanceCoreValues()->exists()) {
            return redirect()->route(
                'teacher.attendance-core-values.edit',
                $enrollment->attendanceCoreValues
            );
        }

        return view('teacher.attendance-core-values.create', [
            'enrollment' => $enrollment->load(['yearLevel', 'schoolYear', 'student'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $enrollment = StudentEnrollment::findOrFail($validated['student_enrollment_id']);

        // Check existing records
        if ($enrollment->attendanceCoreValues()->exists()) {
            return back()->with('error', 'SF9 record already exists for this enrollment period!')
                ->withInput();
        }

        $enrollment->attendanceCoreValues()->create($validated);

        return redirect()->route('teacher.students.index')->with('success', 'Attendance & Core Values record created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceCoreValue $attendanceCoreValue): View
    {
        $enrollment = $attendanceCoreValue->studentEnrollment()->with([
            'student',
            'yearLevel',
            'schoolYear',
        ])->firstOrFail();

        return view('teacher.attendance-core-values.create', [
            'enrollment' => $enrollment,
            'record' => $attendanceCoreValue  // Pass the existing record to the view
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceCoreValue $attendance)
    {
        $data = $request->all();

        // Convert empty values to null for all month fields
        $months = ['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'];
        foreach ($months as $month) {
            $data[$month.'_days'] = $request->input($month.'_days') ?: null;
            $data[$month.'_present'] = $request->input($month.'_present') ?: null;
        }

        $attendance->update($data);

        return redirect()->back()->with('success', 'Attendance updated successfully');
    }

    /**
     * Validation rules for store and update methods
     */
    protected function validationRules(): array
    {
        return [
            'student_enrollment_id' => 'required|exists:student_enrollments,id',
            // Attendance months validation
            'jun_days' => 'nullable|integer|min:0',
            'jun_present' => 'nullable|integer|min:0',
            'jul_days' => 'nullable|integer|min:0',
            'jul_present' => 'nullable|integer|min:0',
            'aug_days' => 'nullable|integer|min:0',
            'aug_present' => 'nullable|integer|min:0',
            'sept_days' => 'nullable|integer|min:0',
            'sept_present' => 'nullable|integer|min:0',
            'oct_days' => 'nullable|integer|min:0',
            'oct_present' => 'nullable|integer|min:0',
            'nov_days' => 'nullable|integer|min:0',
            'nov_present' => 'nullable|integer|min:0',
            'dec_days' => 'nullable|integer|min:0',
            'dec_present' => 'nullable|integer|min:0',
            'jan_days' => 'nullable|integer|min:0',
            'jan_present' => 'nullable|integer|min:0',
            'feb_days' => 'nullable|integer|min:0',
            'feb_present' => 'nullable|integer|min:0',
            'mar_days' => 'nullable|integer|min:0',
            'mar_present' => 'nullable|integer|min:0',
            'apr_days' => 'nullable|integer|min:0',
            'apr_present' => 'nullable|integer|min:0',

            // Core Values validation
            'maka_diyos_q1' => 'nullable|string',
            'maka_diyos_q2' => 'nullable|string',
            'maka_diyos_q3' => 'nullable|string',
            'maka_diyos_q4' => 'nullable|string',
            'makatao_q1' => 'nullable|string',
            'makatao_q2' => 'nullable|string',
            'makatao_q3' => 'nullable|string',
            'makatao_q4' => 'nullable|string',
            'maka_kalikasan_q1' => 'nullable|string',
            'maka_kalikasan_q2' => 'nullable|string',
            'maka_kalikasan_q3' => 'nullable|string',
            'maka_kalikasan_q4' => 'nullable|string',
            'makabansa_q1' => 'nullable|string',
            'makabansa_q2' => 'nullable|string',
            'makabansa_q3' => 'nullable|string',
            'makabansa_q4' => 'nullable|string',
        ];
    }
}
