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

        return new Student([
            'user_id'           => Auth::id(),
            'LRN_num'           => $row['lrn_num'],
            'name'              => $row['name'],
            'age'               => $row['age'],
            'gender'            => $row['gender'],
            'birthdate'         => Carbon::parse($row['birthdate'])->format('Y-m-d'),
            'section'           => $row['section'],
            'year_level_id'     => $row['year_level_id'],
            'school_year_id'    => $row['school_year_id'],
        ]);
    }
}

