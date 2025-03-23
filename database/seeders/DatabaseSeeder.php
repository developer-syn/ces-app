<?php

namespace Database\Seeders;

use App\Models\SchoolInfo;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use App\Models\Quarter;
use App\Models\YearLevel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // First create the year levels
        $yearLevels = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        foreach ($yearLevels as $level) {
            $yearLevel = YearLevel::create(['name' => $level]);
        }

        // Create multiple subjects
        $subjects = [
            'English', 'Filipino', 'Mathematics', 'Science', 
            'Araling Panlipunan', 'EsP', 'EEP', 'MAPEH', 
            'Music', 'Art', 'Physical Education', 'Health'
        ];

        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }

        // First create the school year
        SchoolYear::create([
            'name' => '2024 - 2025',
        ]);

        // Create quarters
        $quarters = [
            'First Quarter',
            'Second Quarter',
            'Third Quarter',
            'Fourth Quarter',
        ];

        foreach ($quarters as $quarter) {
            Quarter::create(['name' => $quarter]);
        }

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'section' => '',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create teacher user with the created year level id
        User::create([
            'name' => 'Teacher User',
            'section' => 'Honest',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'year_level_id' => $yearLevel->id, // use the id of the created year level
        ]);

        SchoolInfo::create([
            'school_id' => '131482',
            'school_name' => 'Caloc-an Elementary School',
            'region' => 'XIII',
            'division' => 'Agusan del Norte',
            'district' => 'Magallanes',
        ]);
    }
}
