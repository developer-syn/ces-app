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
use App\Models\StudentEnrollment;
use Illuminate\Database\Seeder;

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

         // 2. Create year levels in order
         $yearLevelsOrder = ['I', 'II', 'III', 'IV', 'V', 'VI'];
         foreach ($yearLevelsOrder as $level) {
             YearLevel::firstOrCreate(['name' => $level]);
         }
         $orderedYearLevels = YearLevel::orderBy('id')->get();

        $sections = ['Honest', 'Integrity'];

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

        $subjects = Subject::all();

        // 3. Create school years with current flag
        $schoolYears = [];
        $currentYear = 2024;
        for ($i = 0; $i <= 5; $i++) { // Only create 6 years (I-VI)
            $start = $currentYear + $i;
            $end = $start + 1;
            $schoolYears[] = SchoolYear::firstOrCreate([
                'name' => "$start-$end",
                'current' => $i === 0 // Mark first year as current
            ]);
        }

        $quartersArray = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
        foreach ($quartersArray as $quarterName) {
            Quarter::firstOrCreate(['name' => $quarterName]);
        }

        $quarters = Quarter::all();

        // Create admin
        User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'school_info_id' => 1,
        ]);
        // Create teachers
        $teachers = [];
        $yearLevels = YearLevel::all();

        foreach ($yearLevels as $yearLevel) {
            foreach ($sections as $section) {
                $teacher = User::factory()->create([
                    'role' => 'teacher',
                    'year_level_id' => $yearLevel->id,
                    'section' => $section,
                    'school_info_id' => 1,
                    'created_by' => 1,
                ]);
                $teachers[$yearLevel->id][$section] = $teacher;
            }
        }

        // Create 100 regular students
        $students = Student::factory()->count(100)->create();

        foreach ($students as $student) {
            $assignedSection = $sections[array_rand($sections)];
            $teacher = $teachers[$student->year_level_id][$assignedSection] ?? null;

            if ($teacher) {
                $student->user_id = $teacher->id;
                $student->section = $assignedSection;
                $student->save();
            }
        }

        // Assign correct school year based on year level for each student
        foreach ($students as $student) {
            $yearLevelIndex = $orderedYearLevels->search(function ($yl) use ($student) {
                return $yl->id === $student->year_level_id;
            });

            $matchedSchoolYear = $schoolYears[$yearLevelIndex] ?? end($schoolYears);

            foreach ($subjects as $subject) {
                foreach ($quarters as $quarter) {
                    ClassRecord::factory()->create([
                        'student_id' => $student->id,
                        'year_level_id' => $student->year_level_id,
                        'subject_id' => $subject->id,
                        'quarter_id' => $quarter->id,
                        'school_year_id' => $matchedSchoolYear->id,
                        'user_id' => $teachers[$student->year_level_id][$student->section]->id,
                        'grade_section' => "{$orderedYearLevels[$yearLevelIndex]->name} - {$student->section}",
                    ]);
                }
            }
        }

        /**
         * ✅ One student from Year Level 1 (promoted with complete grades)
         */
        /**
         * ✅ One student from Year Level 1 to 2 (promoted with complete grades)
         */
        /**
         * ✅ One student from Year Level 1 to 3 (promoted with complete grades)
         */
        /**
         * ✅ One student from Year Level 1 to 4 (promoted with complete grades)
         */
        /**
         * ✅ One student from Year Level 1 to 5 (promoted with complete grades)
         */
        /**
         * ✅ One student from Year Level 1 to 6 (promoted with complete grades)
         */
        $singleStudent = Student::factory()->create([
            'firstname' => 'Promoted',
            'lastname' => 'Student',
        ]);

        foreach ($orderedYearLevels as $index => $yearLevel) {
            $randomSection = $sections[array_rand($sections)];
            $teacher = $teachers[$yearLevel->id][$randomSection] ?? null;
            $schoolInfo = $student->school_info_id;

            if ($teacher) {
                $schoolYear = $schoolYears[$index] ?? end($schoolYears); // fallback

                StudentEnrollment::create([
                    'student_id' => $singleStudent->id,
                    'year_level_id' => $yearLevel->id,
                    'school_year_id' => $schoolYear->id,
                    'user_id' => $teacher->id,
                    'school_info_id' => $schoolInfo->id,
                ]);

                foreach ($subjects as $subject) {
                    foreach ($quarters as $quarter) {
                        ClassRecord::factory()->create([
                            'student_id' => $singleStudent->id,
                            'year_level_id' => $yearLevel->id,
                            'subject_id' => $subject->id,
                            'quarter_id' => $quarter->id,
                            'school_year_id' => $schoolYear->id,
                            'user_id' => $teacher->id,
                            'grade_section' => "{$yearLevel->name} - {$randomSection}",
                        ]);
                    }
                }
            }
        }
    }
}
