<?php

namespace Database\Factories;

use App\Models\SchoolInfo;
use App\Models\Student;
use App\Models\User;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        // Get a random teacher or create one if none exists
        $teacher = User::where('role', 'teacher')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'teacher']);

        // Generate a random age between 11 and 13
        $age = $this->faker->numberBetween(11, 13);

        // Calculate birthdate based on the generated age
        $birthdate = Carbon::now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30))->toDateString();

        return [
            'user_id' => $teacher->id,
            'LRN_num' => $this->faker->unique()->numerify('##########'),
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->optional()->lastName,
            'lastname' => $this->faker->lastName,
            'suffix' => $this->faker->optional()->suffix,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $age,
            'birthdate' => $birthdate,
            'section' => $teacher->section,
            'year_level_id' => YearLevel::inRandomOrder()->first()?->id ?? YearLevel::factory()->create()->id,
            'school_year_id' => SchoolYear::inRandomOrder()->first()?->id ?? SchoolYear::factory()->create()->id,
            // 'year_level_id' => $teacher->yearLevels->id ?? YearLevel::inRandomOrder()->first()->id,
            // 'school_year_id' => $teacher->schoolYears->id ?? SchoolYear::inRandomOrder()->first()->id,
            'school_info_id' => $teacher->school_info_id,
        ];
    }
}
