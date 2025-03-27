<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check for duplicate LRN_num
        if (Student::where('LRN_num', $row['lrn_num'])->exists()) {
            session()->flash('error', 'Duplicate entry for LRN_num: ' . $row['lrn_num']);
            return null;
        }

        // Handle custom date format for birthdate
        $birthdate = null;
        if (!empty($row['birthdate'])) {
            $acceptedFormats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d']; // Add all acceptable formats here
            foreach ($acceptedFormats as $format) {
                try {
                    $birthdate = Carbon::createFromFormat($format, $row['birthdate'])->format('Y-m-d');
                    break; // Exit the loop if a valid format is found
                } catch (\Exception $e) {
                    // Continue to the next format if the current one fails
                }
            }

            // If no valid format was found, flash an error and skip the row
            if (!$birthdate) {
                session()->flash('error', 'Invalid date format for birthdate: ' . $row['birthdate']);
                return null;
            }
        }

        // Split the name into parts
        $nameParts = array_map('trim', explode(',', $row['name'])); // Split and trim each part
        $lastname = $nameParts[0] ?? ''; // Extract lastname
        $firstname = $nameParts[1] ?? ''; // Extract firstname
        $middlename = $nameParts[2] ?? ''; // Extract middlename
        $suffix = $nameParts[3] ?? ''; // Extract suffix

        return new Student([
            'user_id'           => Auth::id(),
            'LRN_num'           => $row['lrn_num'],
            'lastname'          => $lastname,
            'firstname'         => $firstname,
            'middlename'        => $middlename,
            'suffix'            => $suffix,
            'age'               => $row['age'],
            'gender'            => $row['gender'],
            'birthdate'         => $birthdate,
            'section'           => $row['section'],
            'year_level_id'     => $row['year_level_id'],
            'school_year_id'    => $row['school_year_id'],
        ]);
    }
}
