<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\YearLevel;
use App\Models\Subject;
use App\Models\Quarter;
use App\Models\SchoolYear;

class ConfigurationsController extends Controller
{
    public function index()
    {
        $yearLevels = YearLevel::all();
        $subjects   = Subject::all();
        $schoolYears   = SchoolYear::all();
        $quarters   = Quarter::all();

        return view('teacher.year-levels-subjects-school-years-quarters.index', compact('yearLevels', 'subjects', 'schoolYears', 'quarters'));
    }
}
