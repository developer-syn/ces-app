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
    ];

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


}
