<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentEnrollment;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\SchoolInfo;
use App\Models\User;

$factory->define(StudentEnrollment::class, function () {
    $student = Student::factory()->create();

    return [
        'student_id' => Student::factory(),
        'age' => $student->age,
        'section' => $student->section,
        'year_level_id' => $student->year_level_id,
        'school_year_id' => $student->school_year_id,
        'school_info_id' => $student->school_info_id,
        'user_id' => User::where('role', 'teacher')->inRandomOrder()->first()->id,
    ];
});
