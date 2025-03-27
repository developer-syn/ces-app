<?php

namespace App\Http\Controllers\Teacher;

use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\ClassRecord;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        // Check if all grades are completed for 1st to 4th quarters
        $quarters = [1, 2, 3, 4];
        $completedQuarters = ClassRecord::where('student_id', $student->id)
            ->whereIn('quarter_id', $quarters)
            ->whereNotNull('quarterly_grade') // Ensure the quarterly grade is not null
            ->distinct('quarter_id')
            ->pluck('quarter_id')
            ->toArray();

        // Ensure all quarters (1 to 4) are present in the completed quarters
        if (array_diff($quarters, $completedQuarters)) {
            return redirect()->back()->with('error', 'The student cannot be promoted because not all grades for 1st to 4th quarters are completed.');
        }
        
        // Find the next school year
        $currentSchoolYear = $student->school_year_id;
        $nextSchoolYear = SchoolYear::where('id', '>', $currentSchoolYear)->orderBy('id')->first();

        // Automatically create the next school year if it doesn't exist
        if (!$nextSchoolYear) {
            $currentSchoolYearRecord = SchoolYear::find($currentSchoolYear);

            if ($currentSchoolYearRecord) {
                // Generate the next school year name (e.g., "2024 - 2025" -> "2025 - 2026")
                preg_match('/(\d{4})\s*-\s*(\d{4})/', $currentSchoolYearRecord->name, $matches);

                if (count($matches) === 3) {
                    $startYear = (int) $matches[1] + 1;
                    $endYear = (int) $matches[2] + 1;
                    $nextSchoolYearName = "$startYear - $endYear";

                    // Create the next school year
                    $nextSchoolYear = SchoolYear::create([
                        'name' => $nextSchoolYearName,
                        'current' => false, // Set as non-current by default
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Failed to generate the next school year.');
                }
            } else {
                return redirect()->back()->with('error', 'Current school year record not found.');
            }
        }

        // Update the student's year level and school year
        $student->update([
            'year_level_id' => $nextYearLevel->id,
            'school_year_id' => $nextSchoolYear->id,
        ]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student promoted to ' . $nextYearLevel->name . ' for the school year ' . $nextSchoolYear->name . ' successfully.');
    }
}
