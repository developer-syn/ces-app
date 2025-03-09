<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'user_id'           => Auth::id(),
            'LRN_num'           => $row['lrn_num'],
            'name'              => $row['name'],
            'gender'            => $row['gender'],
            'birthdate'         => $row['birthdate'],
            'section'           => $row['section'],
            'year_level_id'     => $row['year_level_id'],
            'school_year_id'    => $row['school_year_id'],
        ]);
    }
}

