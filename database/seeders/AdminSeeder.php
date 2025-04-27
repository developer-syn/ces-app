<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        DB::table('school_infos')->insert([
            'school_id' => '131482',
            'school_name' => 'Caloc-an Elementary School',
            'region' => 'XIII',
            'division' => 'Agusan del Norte',
            'district' => 'Magallanes',
            'principal_name' => 'Jean Ville E. Sulapas',
            'logo_path' => asset('img/caloc-anLogo.png'),
            'address' => 'Caloc-an, Magallanes, Agusan del Norte',
        ]);

        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('Password@123'),
            'role' => 'admin',
            'school_info_id' => 1,
        ]);

        // 2. Create year levels in order
        $yearLevelsOrder = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        foreach ($yearLevelsOrder as $level) {
            DB::table('year_levels')->insert(['name' => $level]);
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
            DB::table('subjects')->insert(['name' => $subject]);
        }

        $quartersArray = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
        foreach ($quartersArray as $quarterName) {
            DB::table('quarters')->insert(['name' => $quarterName]);
        }

        $school_years = ['2024 - 2025', '2025 - 2026'];
        foreach ($school_years as $school_year) {
            DB::table('school_years')->insert(['name' => $school_year]);
        }
    }
}
