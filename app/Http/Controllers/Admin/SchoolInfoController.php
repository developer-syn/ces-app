<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;

class SchoolInfoController extends Controller
{
    /**
     * Display a listing of the school infos.
     */
    public function index()
    {
        $schoolInfos = SchoolInfo::all();
        return view('admin.school-infos.index', compact('schoolInfos'));
    }

    /**
     * Show the form for creating a new school info.
     */
    public function create()
    {
        return view('admin.school-info.create');
    }

    /**
     * Store a newly created school info in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region'         => 'required|string|max:255',
            'division'       => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'school_name'    => 'required|string|max:255',
            'school_id'      => 'required|string|max:255',
            'principal_name' => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'logo_path'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;

        // Handle file upload manually
        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $destinationPath = public_path('img'); // Save in the 'public/img' directory

            // Create the directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $logoPath = 'img/' . $fileName; // Store the relative path
        }

        // Create the school info record
        SchoolInfo::create([
            'region'         => $validated['region'],
            'division'       => $validated['division'],
            'district'       => $validated['district'],
            'school_name'    => $validated['school_name'],
            'school_id'      => $validated['school_id'],
            'principal_name' => $validated['principal_name'],
            'address'        => $validated['address'],
            'logo_path'      => $logoPath,
        ]);

        return redirect()->route('admin.school-infos.index')
            ->with('success', 'School info created successfully.');
    }

    /**
     * Display the specified school info.
     */
    public function show(SchoolInfo $schoolInfo)
    {
        return view('admin.school-infos.show', compact('schoolInfo'));
    }

    /**
     * Show the form for editing the specified school info.
     */
    public function edit(SchoolInfo $schoolInfo)
    {
        return view('admin.school-infos.edit', compact('schoolInfo'));
    }

    /**
     * Update the specified school info in storage.
     */
    public function update(Request $request, SchoolInfo $schoolInfo)
    {
        $validated = $request->validate([
            'region'            => 'required|string|max:255',
            'division'          => 'required|string|max:255',
            'district'          => 'required|string|max:255',
            'school_name'       => 'required|string|max:255',
            'school_id'         => 'required|string|max:255',
            'principal_name'    => 'required|string|max:255',
            'address'           => 'required|string|max:255',
            'logo_path'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;

        // Handle file upload manually
        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $destinationPath = public_path('img'); // Save in the 'public/img' directory

            // Create the directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $logoPath = 'img/' . $fileName; // Store the relative path
        }

        // Update the school info record
        $schoolInfo->update([
            'region'         => $validated['region'],
            'division'       => $validated['division'],
            'district'       => $validated['district'],
            'school_name'    => $validated['school_name'],
            'school_id'      => $validated['school_id'],
            'principal_name' => $validated['principal_name'],
            'address'        => $validated['address'],
            'logo_path'      => $logoPath, // Update the logo path
        ]);

        return redirect()->route('admin.school-infos.index')
            ->with('success', 'School info updated successfully.');
    }

    /**
     * Remove the specified school info from storage.
     */
    public function destroy(SchoolInfo $schoolInfo)
    {
        $schoolInfo->delete();

        return redirect()->route('admin.school-infos.index')
            ->with('success', 'School info deleted successfully.');
    }
}
