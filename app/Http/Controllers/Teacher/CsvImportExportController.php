<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CsvImportExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function import(Request $request)
    {
        // Validate that a file is provided and that it's a CSV or TXT file.
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Get the uploaded file.
        $file = $request->file('file');

        // Open the file for reading.
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Read the header row and normalize it (e.g., "Name" becomes "name").
            $header = fgetcsv($handle, 1000, ',');
            $header = array_map(function ($value) {
                return strtolower(trim($value));
            }, $header);

            \Log::info('CSV Header:', $header);

            // Loop through each row.
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Skip rows that don't match the header length.
                if (count($row) !== count($header)) {
                    \Log::warning('Row skipped due to mismatched columns:', ['row' => $row]);
                    continue;
                }

                // Combine header and row to create an associative array.
                $data = array_combine($header, $row);
                \Log::info('Processing row:', $data);

                // Skip the row if the 'name' field is empty.
                if (empty(trim($data['name'] ?? ''))) {
                    \Log::info('Row skipped because name is empty', $data);
                    continue;
                }

                // Convert the birthdate to the proper format (YYYY-MM-DD) if present.
                $birthdate = $data['birthdate'] ?? null;
                if ($birthdate) {
                    try {
                        // Assuming the CSV date is in "m/d/Y" format.
                        $birthdate = Carbon::createFromFormat('m/d/Y', $birthdate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        \Log::error('Birthdate conversion failed', [
                            'birthdate' => $birthdate,
                            'error' => $e->getMessage()
                        ]);
                        continue; // Skip this row if conversion fails.
                    }
                }

                // Create a new Student record.
                Student::create([
                    'user_id'        => Auth::id(),  // Associate with the current teacher.
                    'name'           => $data['name'],
                    'section'        => $data['section'] ?? null,
                    'birthdate'      => $birthdate,
                    'year_level_id'  => $data['year_level_id'] ?? null,
                    'school_year_id' => $data['school_year_id'] ?? null,
                ]);

                \Log::info('Student imported successfully', ['name' => $data['name']]);
            }
            fclose($handle);
        }

        return redirect()->route('teacher.students.index')
            ->with('success', 'Students imported successfully.');
    }



    public function deleteSelected(Request $request)
    {
        $studentIds = $request->input('students', []);
        if (!empty($studentIds)) {
            // Optionally, add a check to ensure the current teacher owns these students:
            \App\Models\Student::whereIn('id', $studentIds)
                ->where('user_id', auth()->id())
                ->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No students selected.']);
    }
}
