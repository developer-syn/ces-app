<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\YearLevel;
use App\Models\SchoolInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Rules\StrongPassword;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::with('yearLevel','schoolInfo')
                            ->where('role', 'teacher')
                            ->where('created_by', auth()->id())
                            ->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $yearLevels = YearLevel::all();
        $school_infos = SchoolInfo::all();
        return view('admin.teachers.create', compact('yearLevels', 'school_infos'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), new StrongPassword],
            'role'      => ['required', 'in:admin,teacher'],
            'year_level_id' => ['required_if:role,teacher', 'exists:year_levels,id'],
            'school_info_id' => ['nullable', 'exists:school_infos,id'],
        ]);

        $validated['created_by'] = Auth::id(); // Assign admin ID

        User::create($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher)
    {
        $admin = auth()->user();

        // Allow only if admin and teacher belong to the same school_info
        if ($admin->role === 'admin' && $teacher->school_info_id !== $admin->school_info_id) {
            abort(403, 'Unauthorized access to edit this teacher.');
        }

        $yearLevels = YearLevel::all();
        $school_infos = SchoolInfo::all();

        return view('admin.teachers.edit', compact('teacher', 'yearLevels', 'school_infos'));
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'role'      => ['required', 'in:admin,teacher'],
            'year_level_id' => ['required_if:role,teacher', 'exists:year_levels,id'],
            'school_info_id' => 'nullable|exists:school_infos,id',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $teacher->name = $validated['name'];
        $teacher->section = $validated['section'] ?? null;
        $teacher->email = $validated['email'];
        $teacher->role = $validated['role'];
        $teacher->year_level_id = $validated['year_level_id'];
        $teacher->school_info_id = $validated['school_info_id'];

        if ($request->filled('password')) {
            $teacher->password = Hash::make($validated['password']);
        }

        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
{
    // Delete related student enrollments
    $teacher->studentEnrollments()->delete();

    // Now delete the teacher
    $teacher->delete();

    return redirect()->route('admin.teachers.index')
        ->with('success', 'Teacher deleted successfully.');
}
}
