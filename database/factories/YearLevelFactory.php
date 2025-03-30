<?php

namespace Database\Factories;

use App\Models\YearLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class YearLevelFactory extends Factory
{
    protected $model = YearLevel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['I', 'II', 'III', 'IV', 'V', 'VI']),
        ];
    }
}


