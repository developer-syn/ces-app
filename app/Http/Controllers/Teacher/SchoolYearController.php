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
        ]);
        SchoolYear::create($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level created successfully.');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $schoolYear->update($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level updated successfully.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Year Level deleted successfully.');
    }
}
