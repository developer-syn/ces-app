<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearLevel;
use App\Models\Subject;

class ConfigurationsController extends Controller
{
    public function index()
    {
        $yearLevels = YearLevel::all();
        $subjects   = Subject::all();
        return view('admin.year-levels-subjects.index', compact('yearLevels', 'subjects'));
    }
}
