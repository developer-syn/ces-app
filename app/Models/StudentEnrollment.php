<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'age',
        'section',
        'year_level_id',
        'school_year_id',
        'user_id',
        'school_info_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function user()
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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schoolInfo()
    {
        return $this->belongsTo(SchoolInfo::class);
    }

    public function attendanceCoreValues()
    {
        return $this->hasOne(AttendanceCoreValue::class);
    }
    
}
