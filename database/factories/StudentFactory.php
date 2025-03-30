<?php

namespace Database\Factories;

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
        // Generate a random age between 11 and 13
        $age = $this->faker->numberBetween(11, 13);

        // Calculate birthdate based on the generated age
        $birthdate = Carbon::now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30))->toDateString();

        return [
            'user_id' => User::factory()->create(['role' => 'student'])->id,
            'LRN_num' => $this->faker->unique()->numerify('##########'),
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->optional()->lastName,
            'lastname' => $this->faker->lastName,
            'suffix' => $this->faker->optional()->suffix,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $age, // Set age based on birthdate calculation
            'birthdate' => $birthdate, // Computed birthdate
            'section' => $this->faker->randomElement(['A', 'B', 'C']),
            'year_level_id' => YearLevel::inRandomOrder()->first()->id ?? YearLevel::factory(),
            'school_year_id' => SchoolYear::inRandomOrder()->first()->id ?? SchoolYear::factory(),
        ];
    }
}
