<?php

namespace Database\Seeders;

use App\Models\SchoolInfo;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use App\Models\Quarter;
use App\Models\YearLevel;
use App\Models\Student;
use App\Models\ClassRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create school information
        SchoolInfo::create([
            'school_id' => '131482',
            'school_name' => 'Caloc-an Elementary School',
            'region' => 'XIII',
            'division' => 'Agusan del Norte',
            'district' => 'Magallanes',
            'principal_name' => 'Jean Ville E. Sulapas',
            'logo_path' => asset('img/caloc-anLogo.png'),
        ]);

        // First create the year levels manually
        $yearLevels = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        foreach ($yearLevels as $level) {
            YearLevel::firstOrCreate(['name' => $level]);
        }

        // Retrieve all year levels
        $yearLevels = YearLevel::all();

        // Define sections
        $sections = ['Honest', 'Integrity'];

        // Create multiple subjects
        $subjects = [
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

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['name' => $subject]);
        }

        // Fetch all subjects
        $subjects = Subject::all();

        // First create the school year
        $schoolYear = SchoolYear::firstOrCreate([
            'name' => '2024 - 2025',
        ]);

        // Create four quarters
        $quarters = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
        foreach ($quarters as $quarterName) {
            Quarter::firstOrCreate(['name' => $quarterName]);
        }

        // Fetch the existing quarters
        $quarters = Quarter::all();

        // Create an admin user
        User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create teachers and assign them to each year level and section
        $teachers = [];
        foreach ($yearLevels as $yearLevel) {
            foreach ($sections as $section) {
                $teacher = User::factory()->create([
                    'role' => 'teacher',
                    'year_level_id' => $yearLevel->id,
                    'section' => $section,
                ]);
                $teachers[$yearLevel->id][$section] = $teacher; // Store teachers by year level & section
            }
        }

        // Create 100 students and assign them to teachers by section
        $students = Student::factory()->count(100)->create();

        foreach ($students as $student) {
            // Randomly assign a section from available ones
            $assignedSection = $sections[array_rand($sections)];

            // Assign students to a teacher from their year level & section
            $teacher = $teachers[$student->year_level_id][$assignedSection] ?? null;
            if ($teacher) {
                $student->user_id = $teacher->id;
                $student->section = $assignedSection;
                $student->save();
            }
        }

        // Assign class records for each student, per subject, per quarter
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                foreach ($quarters as $quarter) {
                    ClassRecord::factory()->create([
                        'student_id' => $student->id,
                        'year_level_id' => $student->year_level_id,
                        'subject_id' => $subject->id,
                        'quarter_id' => $quarter->id,
                        'school_year_id' => $schoolYear->id,
                        'user_id' => $teachers[$student->year_level_id][$student->section]->id, // Assign teacher to class record
                        'grade_section' => "{$student->year_level_id} - {$student->section}",
                    ]);
                }
            }
        }
    }
}
