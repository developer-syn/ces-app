<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\YearLevel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::with('yearLevel')->where('role', 'teacher')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $yearLevels = YearLevel::all();
        return view('admin.teachers.create', compact('yearLevels'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'max:100'],
            'year_level_id' => ['required', 'exists:year_levels,id'],
        ]);

        User::create([
            'name' => $validated['name'],
            'section' => $validated['section'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'year_level_id' => $validated['year_level_id'],
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher)
    {
        $yearLevels = YearLevel::all();
        return view('admin.teachers.edit', compact('teacher', 'yearLevels'));
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'role' => 'required|string|max:100',
            'year_level_id' => 'required|exists:year_levels,id',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $teacher->name = $validated['name'];
        $teacher->section = $validated['section'];
        $teacher->email = $validated['email'];
        $teacher->role = $validated['role'];
        $teacher->year_level_id = $validated['year_level_id'];

        if ($request->filled('password')) {
            $teacher->password = Hash::make($validated['password']);
        }

        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
