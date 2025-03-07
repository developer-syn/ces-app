<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
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
}
