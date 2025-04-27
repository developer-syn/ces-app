<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolInfo;
use App\Models\User;
use App\Models\YearLevel;
use App\Models\Subject;
use App\Models\SchoolYear;
use App\Models\Quarter;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolInfo::create([
            'school_id' => '131482',
            'school_name' => 'Caloc-an Elementary School',
            'region' => 'XIII',
            'division' => 'Agusan del Norte',
            'district' => 'Magallanes',
            'principal_name' => 'Jean Ville E. Sulapas',
            'logo_path' => asset('img/caloc-anLogo.png'),
            'address' => 'Caloc-an, Magallanes, Agusan del Norte',
        ]);

        User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'school_info_id' => 1,
        ]);

        // 2. Create year levels in order
        $yearLevelsOrder = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        foreach ($yearLevelsOrder as $level) {
            YearLevel::firstOrCreate(['name' => $level]);
        }

        $subjectsArray = [
            'Mother Tongue',
            'Filipino',
            'English',
            'Mathematics',
            'Science',
            'Araling Panlipunan',
            'EEP / TLE',
            'Music',
            'Arts',
            'Physical Education',
            'Health',
            'Eduk. sa Pagpapakatao',
        ];

        foreach ($subjectsArray as $subject) {
            Subject::firstOrCreate(['name' => $subject]);
        }

        $quartersArray = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
        foreach ($quartersArray as $quarterName) {
            Quarter::firstOrCreate(['name' => $quarterName]);
        }
    }
}
