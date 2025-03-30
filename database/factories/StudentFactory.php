<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'student'])->id,
            'LRN_num' => $this->faker->unique()->numerify('##########'),
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->optional()->lastName,
            'lastname' => $this->faker->lastName,
            'suffix' => $this->faker->optional()->suffix,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $this->faker->numberBetween(6, 12),
            'section' => $this->faker->randomElement(['A', 'B', 'C']),
            'birthdate' => $this->faker->date(),
            'year_level_id' => YearLevel::inRandomOrder()->first()->id ?? YearLevel::factory(),
            'school_year_id' => SchoolYear::inRandomOrder()->first()->id ?? SchoolYear::factory(),
        ];
    }
}

