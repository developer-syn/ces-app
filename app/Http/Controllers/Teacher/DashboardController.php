<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRecord;
use App\Models\YearLevel;
use App\Models\Subject;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalTeachers = User::where('role', 'teacher')->count();
        $totalSubjects = Subject::count();

        if ($user->role === 'admin') {
            // Admin sees only teachers in their school
            $totalTeachers = User::where('role', 'teacher')
                ->where('school_info_id', $user->school_info_id)
                ->count();

            // Admin sees total class records for their school only
            $totalClasses = ClassRecord::whereHas('user', function ($q) use ($user) {
                $q->where('school_info_id', $user->school_info_id);
            })->distinct('subject_id')->count();

            // Admin sees students per grade level & section in their school
            $studentsPerGrade = Student::where('school_info_id', $user->school_info_id)
                ->select('year_level_id', 'section')
                ->selectRaw('COUNT(*) as student_count')
                ->groupBy('year_level_id', 'section')
                ->orderBy('year_level_id')
                ->orderBy('section')
                ->get()
                ->groupBy('year_level_id');

            // Admin sees class records within their school
            $classRecords = ClassRecord::with(['user', 'subject', 'student'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('school_info_id', $user->school_info_id);
                })
                ->orderBy('year_level_id')
                ->orderBy('grade_section')
                ->get();
        } else {
            // Teacher sees only their assigned class records
            $totalClasses = ClassRecord::where('user_id', $user->id)
                ->distinct('subject_id')
                ->count();

            // Teacher sees only their assigned students
            $studentsPerGrade = Student::where('user_id', $user->id)
                ->select('year_level_id', 'section')
                ->selectRaw('COUNT(*) as student_count')
                ->groupBy('year_level_id', 'section')
                ->orderBy('year_level_id')
                ->orderBy('section')
                ->get()
                ->groupBy('year_level_id');

            // Teacher sees only their assigned class records
            $classRecords = ClassRecord::where('user_id', $user->id)
                ->with(['user', 'subject', 'student'])
                ->orderBy('year_level_id')
                ->orderBy('grade_section')
                ->get();
        }

        // ✅ Admin sees all logs, teachers see only their own logs
        $recentActivities = ($user->role === 'admin')
            ? ActivityLog::whereHas('user', function ($q) use ($user) {
                $q->where('school_info_id', $user->school_info_id);
            })->latest()->limit(10)->get()
            : ActivityLog::where('user_id', $user->id)->latest()->limit(10)->get();


        return view('dashboard', compact(
            'totalTeachers',
            'totalClasses',
            'studentsPerGrade',
            'classRecords',
            'recentActivities',
            'totalSubjects'
        ));
    }
}
