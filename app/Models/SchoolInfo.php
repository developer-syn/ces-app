<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'division',
        'district',
        'school_name',
        'school_id',
        'principal_name',
        'logo_path',
    ];
}
