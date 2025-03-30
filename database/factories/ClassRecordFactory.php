<?php

namespace Database\Factories;

use App\Models\ClassRecord;
use App\Models\Student;
use App\Models\User;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Quarter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRecordFactory extends Factory
{
    protected $model = ClassRecord::class;

    public function definition()
    {
        $wwHighestScores = [
            1 => 15,
            2 => 15,
            3 => 15,
            4 => 15,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0
        ];

        $ptHighestScores = [
            1 => 15,
            2 => 15,
            3 => 15,
            4 => 15,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0
        ];

        $writtenWorks = [];
        foreach ($wwHighestScores as $column => $maxScore) {
            $writtenWorks["written_work_{$column}"] = $maxScore > 0 ? $this->faker->numberBetween(8, $maxScore) : 0;
        }

        $performanceTasks = [];
        foreach ($ptHighestScores as $column => $maxScore) {
            $performanceTasks["performance_task_{$column}"] = $maxScore > 0 ? $this->faker->numberBetween(8, $maxScore) : 0;
        }

        $writtenWorksTotal = array_sum(array_values($writtenWorks));
        $performanceTasksTotal = array_sum(array_values($performanceTasks));

        $writtenWorksHighestTotal = array_sum($wwHighestScores);
        $performanceTasksHighestTotal = array_sum($ptHighestScores);

        $wwPS = $writtenWorksHighestTotal > 0 ? round(($writtenWorksTotal / $writtenWorksHighestTotal) * 100) : 0;
        $ptPS = $performanceTasksHighestTotal > 0 ? round(($performanceTasksTotal / $performanceTasksHighestTotal) * 100) : 0;

        $wwWS = round(($wwPS * 0.3), 0);
        $ptWS = round(($ptPS * 0.5), 0);

        $global_hqa = 50;
        $quarterlyAssessment = $this->faker->numberBetween(30, $global_hqa);
        $qaPS = round(($quarterlyAssessment / $global_hqa) * 100);
        $qaWS = round(($qaPS * 0.2), 0);

        $initialGrade = $wwWS + $ptWS + $qaWS;
        $quarterlyGrade = $initialGrade;

        return array_merge([
            'user_id' => User::where('role', 'teacher')->inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'student_id' => Student::inRandomOrder()->first()->id ?? Student::factory()->create()->id,
            'year_level_id' => YearLevel::inRandomOrder()->first()->id ?? YearLevel::factory()->create()->id,
            'quarter_id' => Quarter::inRandomOrder()->first()->id ?? Quarter::factory()->create()->id,
            'subject_id' => Subject::inRandomOrder()->first()->id ?? Subject::factory()->create()->id,
            'school_year_id' => SchoolYear::inRandomOrder()->first()->id ?? SchoolYear::factory()->create()->id,
            'grade_section' => $this->faker->randomElement(['A', 'B', 'C']),
            'teacher' => $this->faker->name,
            'written_works_total' => $writtenWorksTotal,
            'written_works_ps' => $wwPS,
            'written_works_ws' => $wwWS,
            'performance_tasks_total' => $performanceTasksTotal,
            'performance_tasks_ps' => $ptPS,
            'performance_tasks_ws' => $ptWS,
            'quarterly_assessment' => $quarterlyAssessment,
            'quarterly_assessment_ps' => $qaPS,
            'quarterly_assessment_ws' => $qaWS,
            'initial_grade' => $initialGrade,
            'quarterly_grade' => $quarterlyGrade,
            'hww' => json_encode($wwHighestScores),
            'hpt' => json_encode($ptHighestScores),
            'global_hqa' => $global_hqa,
        ], $writtenWorks, $performanceTasks);
    }
}
