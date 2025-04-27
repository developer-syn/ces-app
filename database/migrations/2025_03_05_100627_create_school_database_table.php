<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. First create year_levels table
        Schema::create('year_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // create school_info table
        Schema::create('school_infos', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('division');
            $table->string('district');
            $table->string('school_name');
            $table->string('school_id');
            $table->string('principal_name');
            $table->string('address');
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        // 2. Create users(teacher) table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable();
            $table->string('role')->default('teacher');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('year_level_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_info_id')->nullable()->contrained()->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. Create school_years table
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('current')->default(false);
            $table->timestamps();
        });

        // 4. Create subjects table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        // 4. Create quarterly table
        Schema::create('quarters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 5. Create students table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('LRN_num')->unique();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->string('gender');
            $table->string('age');
            $table->string('section')->nullable();
            $table->date('birthdate');
            $table->foreignId('year_level_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_year_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_info_id')->nullable()->contrained()->onDelete('set null');
            $table->timestamps();
        });

        // 6. Create related tables
        Schema::create('class_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('year_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('quarter_id')->constrained()->onDelete('cascade');
            $table->string('grade_section');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('teacher');

            // Written Works scores (10 items)
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("written_work_{$i}", 5, 2)->nullable();
            }
            $table->decimal('written_works_total', 5, 2)->nullable();
            $table->decimal('written_works_ps', 5, 2)->nullable();
            $table->decimal('written_works_ws', 5, 2)->nullable();

            // Performance Tasks scores (10 items)
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("performance_task_{$i}", 5, 2)->nullable();
            }
            $table->decimal('performance_tasks_total', 5, 2)->nullable();
            $table->decimal('performance_tasks_ps', 5, 2)->nullable();
            $table->decimal('performance_tasks_ws', 5, 2)->nullable();

            // Quarterly Assessment
            $table->decimal('quarterly_assessment', 5, 2)->nullable();
            $table->decimal('quarterly_assessment_ps', 5, 2)->nullable();
            $table->decimal('quarterly_assessment_ws', 5, 2)->nullable();

            // Final Grades
            $table->decimal('initial_grade', 5, 2)->nullable();
            $table->decimal('quarterly_grade', 5, 2)->nullable();

            // Global header fields for highest possible scores
            $table->json('hww')->nullable(); // high scores limit 15 points (1, 2, 3 and 4)
            $table->json('hpt')->nullable(); // high scores limit 15 points (1, 2, 3 and 4)
            $table->decimal('global_hqa', 5, 2)->nullable(); // global highest possible average (HQA) for all subjects 50 points

            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('age')->nullable();
            $table->string('section')->nullable();
            $table->foreignId('year_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('school_info_id')->nullable()->contrained()->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('attendance_core_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_enrollment_id')->constrained('student_enrollments')->onDelete('cascade');

            // Attendance Columns (School Days and Present Days)
            $months = ['jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar', 'apr'];
            foreach ($months as $month) {
                $table->integer("{$month}_days")->nullable()->default(null);
                $table->integer("{$month}_present")->nullable()->default(null);
            }

            // Core Values Columns
            $coreValues = [
                'maka_diyos',
                'makatao',
                'maka_kalikasan',
                'makabansa'
            ];

            foreach ($coreValues as $value) {
                for ($q = 1; $q <= 4; $q++) {
                    $table->enum("{$value}_q{$q}", ['AO', 'SO', 'RO', 'NO'])
                        ->nullable()
                        ->comment("Quarter {$q} rating");
                }
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_core_values');
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('class_records');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('school_infos');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('year_levels');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('quarters');
        Schema::dropIfExists('users');
    }
};
