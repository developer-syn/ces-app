<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferredStudent extends Model
{
    protected $fillable = [
        'student_id',
        'year_level_id',
        'school_year_id',
        'school_info_id',
        'section',
        'subject_id',
        'user_id',
        'q1_grade',
        'q2_grade',
        'q3_grade',
        'q4_grade',
        'final_rating',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schoolInfo()
    {
        return $this->belongsTo(SchoolInfo::class);
    }
}
