<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // transfer students table for adding grades only
        Schema::create('transferred_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('year_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_info_id')->nullable()->constrained()->onDelete('set null');
            $table->string('section')->nullable();
            // grade fields
            // subjects
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            /*
            - input grades for each subject and per quarter.
            - note: these fields are nullable to allow for partial data entry for transfer students.
            - no need to input written works and performance tasks and quarterly assessment only input the final grades of the students per quarter.
            - for example, if the student is transferring in the grade 2 level, the teacher will only input the final grades for each subject for each quarter in the grade 1 level.
            */
            $table->decimal('q1_grade', 5, 2)->nullable(); // Quarter 1
            $table->decimal('q2_grade', 5, 2)->nullable(); // Quarter 2
            $table->decimal('q3_grade', 5, 2)->nullable(); // Quarter 3
            $table->decimal('q4_grade', 5, 2)->nullable(); // Quarter 4
            $table->decimal('final_rating', 5, 2)->nullable(); // Final Rating
            $table->string('remarks')->nullable(); // Remarks (Passed, Failed, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferred_students');
    }
};
