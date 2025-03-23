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

class PromoteStudentController extends Controller
{
    public function promote(Request $request, Student $student)
    {
        // Ensure the student is eligible for promotion
        $currentYearLevel = $student->year_level_id;

        // Find the next year level
        $nextYearLevel = YearLevel::where('id', '>', $currentYearLevel)->orderBy('id')->first();

        if (!$nextYearLevel) {
            return redirect()->back()->with('error', 'The student is already in the highest year level.');
        }

        // Preserve the student's current records (no changes needed as they are tied to the current year level)
        // Update the student's year level to the next level
        $student->update(['year_level_id' => $nextYearLevel->id]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student promoted to ' . $nextYearLevel->name . ' successfully.');
    }
}
