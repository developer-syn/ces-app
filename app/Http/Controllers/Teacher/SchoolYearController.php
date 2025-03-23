<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'boolean', // Ensuring it is a boolean
        ]);

        $isCurrent = $request->has('current') ? true : false;

        if ($isCurrent) {
            SchoolYear::query()->update(['current' => false]); // Reset previous current year
        }

        SchoolYear::create([
            'name' => $request->name,
            'current' => $isCurrent,
        ]);

        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')
                         ->with('success', 'School Year created successfully.');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'boolean', // Ensuring it is a boolean
        ]);

        $isCurrent = $request->has('current') ? true : false;

        if ($isCurrent) {
            SchoolYear::query()->update(['current' => false]); // Ensure only one is current
        }

        $schoolYear->update([
            'name' => $request->name,
            'current' => $isCurrent,
        ]);

        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')
                         ->with('success', 'School Year updated successfully.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level deleted successfully.');
    }
}
