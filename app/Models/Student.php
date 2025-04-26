<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    // Define fillable fields for mass assignment
    protected $fillable = [
        'LRN_num',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'age',
        'gender',
        'section',
        'birthdate',
        'year_level_id',
        'school_year_id',
        'user_id',
        'school_info_id',
    ];

    // protected static function booted()
    // {
    //     static::created(function ($student) {
    //         // Automatically add enrollment when a student is created
    //         if ($student->year_level_id && $student->school_year_id && $student->user_id) {
    //             \App\Models\StudentEnrollment::create([
    //                 'student_id'        => $student->id,
    //                 'age'               => $student->age,
    //                 'section'           => $student->section,
    //                 'year_level_id'     => $student->year_level_id,
    //                 'school_year_id'    => $student->school_year_id,
    //                 'user_id'           => $student->user_id, // teacher who added the student
    //             ]);
    //         }
    //     });
    // }


    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

    public function quarter()
    {
        return $this->belongsTo(Quarter::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRecords()
    {
        return $this->hasMany(ClassRecord::class, 'student_id');
    }

    public function summaryQuarterlyGrades()
    {
        return $this->hasMany(SummaryQuarterlyGrade::class, 'student_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class)
            ->with(['yearLevel', 'schoolYear', 'attendanceCoreValues'])
            ->orderBy('school_year_id');
    }

    public function schoolInfo()
    {
        return $this->belongsTo(SchoolInfo::class);
    }

    // app/Models/Student.php
    public function enrollment()
    {
        return $this->hasOne(StudentEnrollment::class);
    }

    public function attendanceCoreValues()
    {
        return $this->hasMany(AttendanceCoreValue::class);
    }

    public function latestEnrollment()
    {
        return $this->hasOne(StudentEnrollment::class)->latest();
    }
    public function student_enrollments()
    {
        return $this->hasOne(StudentEnrollment::class)->latest();
    }
}
