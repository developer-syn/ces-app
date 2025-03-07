<?php

namespace App\Http\Controllers\Admin;

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
        return redirect()->route('admin.year-levels-subjects')->with('success', 'Year Level created successfully.');
    }

    public function update(Request $request, YearLevel $yearLevel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $yearLevel->update($request->only('name'));
        return redirect()->route('admin.year-levels-subjects')->with('success', 'Year Level updated successfully.');
    }

    public function destroy(YearLevel $yearLevel)
    {
        $yearLevel->delete();
        return redirect()->route('admin.year-levels-subjects')->with('success', 'Year Level deleted successfully.');
    }
}
