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
        // First create the year level
        $yearLevel = YearLevel::create([
            'name' => '1',
        ]);

        // First create the Subject
        Subject::create([
            'name' => 'English',
        ]);
        // First create the school year
        SchoolYear::create([
            'name' => '2024 - 2025',
        ]);
        // First create the year level
        Quarter::create([
            'name' => 'First Quarter',
        ]);

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
