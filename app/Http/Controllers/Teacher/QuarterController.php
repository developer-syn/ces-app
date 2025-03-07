<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quarter;
use Illuminate\Http\Request;

class QuarterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Quarter::create($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Quarter created successfully.');
    }

    public function update(Request $request, Quarter $quarter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $quarter->update($request->only('name'));
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Quarter updated successfully.');
    }

    public function destroy(Quarter $quarter)
    {
        $quarter->delete();
        return redirect()->route('teacher.year-levels-subjects-school-years-quarters')->with('success', 'Quarter deleted successfully.');
    }
}
