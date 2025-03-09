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
            $table->unsignedBigInteger('user_id'); // Add user_id right after id
            $table->string('LRN_num')->unique();
            $table->string('name');
            $table->string('gender');
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

            // Basic info fields
            $table->string('grade_section');
            $table->string('quarter');
            $table->string('subject_id');
            $table->string('school_year');
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

        Schema::create('school_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->json('grades');
            $table->timestamps();
        });

        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('quarter_1')->nullable();
            $table->integer('quarter_2')->nullable();
            $table->integer('quarter_3')->nullable();
            $table->integer('quarter_4')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('school_forms');
        Schema::dropIfExists('class_records');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('users');
        Schema::dropIfExists('year_levels');
    }
};
