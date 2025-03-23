<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public static function getCurrentYear()
    {
        return self::where('current', true)->first();
    }
}

