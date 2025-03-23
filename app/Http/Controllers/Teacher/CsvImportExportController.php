<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;

class CsvImportExportController extends Controller
{
    public function import(Request $request)
    {
        try {
            Excel::import(new StudentImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
            return redirect()->back()->with('error', 'There was an error importing the file.');
        }

        return redirect()->route('teacher.students.index')->with('success', 'Students imported successfully.');
    }

    /**
     * Delete multiple selected students.
     */
    public function deleteSelected(Request $request)
    {
        $studentIds = $request->input('students', []);
        if (!empty($studentIds)) {
            // Optionally, add a check to ensure the current teacher owns these students:
            Student::whereIn('id', $studentIds)
                ->where('user_id', auth()->id())
                ->delete();

            return response()->json(['success' => true, 'message' => 'Selected students were deleted.']);
        }
        return response()->json(['success' => false, 'message' => 'No students selected.']);
    }
}
