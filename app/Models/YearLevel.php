<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // Relationship: A YearLevel can have many Students
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Relationship: A User can only have one YearLevel
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function studentEnrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }
}
