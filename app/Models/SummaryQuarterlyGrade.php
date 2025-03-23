<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryQuarterlyGrade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'quarter_1',
        'quarter_2',
        'quarter_3',
        'quarter_4',
        'final_rating',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRecord()
    {
        return $this->belongsTo(ClassRecord::class, 'student_id', 'student_id');
    }
}
