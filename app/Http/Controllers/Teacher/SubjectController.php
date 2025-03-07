<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Subject::create($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $subject->update($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Subject deleted successfully.');
    }
}
