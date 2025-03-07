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
        'name',
        'section',
        'birthdate',
        'year_level_id',
        'school_year_id',
        'user_id',
    ];

    // Define relationship to YearLevel
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
