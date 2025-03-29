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
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        // 2. Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable();
            $table->string('role')->default('teacher');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('year_level_id')->nullable()->constrained()->onDelete('set null');
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
            $table->unsignedBigInteger('user_id');
            $table->string('LRN_num')->unique();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->string('gender');
            $table->string('age');
            $table->string('section');
            $table->date('birthdate');
            $table->foreignId('year_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Create related tables
        Schema::create('class_records', function (Blueprint $table) {
            $table->id();
            // Foreign keys (assuming related tables exist)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            // Add a foreign key constraint if you have a `year_levels` table
            $table->foreignId('year_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('quarter_id')->constrained()->onDelete('cascade');
            // Basic info fields
            $table->string('grade_section');
            $table->string('subject_id');
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
            $table->json('hww')->nullable();
            $table->json('hpt')->nullable();
            $table->decimal('global_hqa', 5, 2)->nullable();

            $table->timestamps();
        });

        // 8. Store quarterly grades per subject for each student
        Schema::create('summary_quarterly_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('quarter_1', 5, 2)->nullable();
            $table->decimal('quarter_2', 5, 2)->nullable();
            $table->decimal('quarter_3', 5, 2)->nullable();
            $table->decimal('quarter_4', 5, 2)->nullable();
            $table->decimal('final_rating', 5, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 9. Store overall school form (per student)
        Schema::create('school_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->json('grades'); // JSON format: { "English": { "Q1": 85, "Q2": 88, "Q3": 90, "Q4": 87, "Final": 88, "Remarks": "Passed" }, ... }
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('school_forms');
        Schema::dropIfExists('school_infos');
        Schema::dropIfExists('class_records');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('users');
        Schema::dropIfExists('year_levels');
    }
};
