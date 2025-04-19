<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentEnrollment;
use App\Models\Student;
use App\Models\User;

class StudentEnrollmentFactory extends Factory
{
    protected $model = StudentEnrollment::class;

    public function definition()
    {
        $student = Student::inRandomOrder()->first() ?? Student::factory()->create();
        $teacher = User::where('role', 'teacher')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'teacher']);

        return [
            'student_id' => $student->id,
            'age' => $student->age,
            'section' => $teacher->section,
            'year_level_id' => $student->year_level_id,
            'school_year_id' => $student->school_year_id,
            'school_info_id' => $student->school_info_id,
            'user_id' => $teacher->id,
        ];
    }
}
