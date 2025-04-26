<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\YearLevel;
use Illuminate\Http\Request;

class YearLevelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        YearLevel::create($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level created successfully.');
    }

    public function update(Request $request, YearLevel $yearLevel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $yearLevel->update($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level updated successfully.');
    }
    
    public function destroy(YearLevel $yearLevel)
    {
        if ($yearLevel->studentEnrollments()->exists()) {
            return redirect()->route('teacher.year-levels-subjects-school-years-quarters')
                ->with('error', 'Cannot delete year level as it has associated enrolled students.');
        }

        $yearLevel->delete();
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level deleted successfully.');
    }
}
