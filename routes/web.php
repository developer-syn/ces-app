<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\Admin\TeacherController;
use \App\Http\Controllers\Admin\SchoolInfoController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\YearLevelController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\QuarterController;
use App\Http\Controllers\Teacher\SchoolYearController;
use App\Http\Controllers\Teacher\ClassRecordController;
use App\Http\Controllers\Teacher\CsvImportExportController;
use App\Http\Controllers\Teacher\ConfigurationsController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\SummaryQuarterlyGradesController;
use App\Http\Controllers\Teacher\PromoteStudentController;
use App\Http\Controllers\Teacher\SchoolForm10Controller;
use App\Http\Controllers\Teacher\AttendanceCoreValuesController;


Route::get('/unauthorized', function () {
    return response()->view('errors.403', ['message' => 'Unauthorized action.'], 403);
})->name('errors.403');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('teachers', TeacherController::class);
    Route::resource('school-infos', SchoolInfoController::class);
});

Route::middleware(['auth', 'role:admin|teacher'])->prefix('teacher', 'admin')->name('teacher.')->group(function () {
    // Class Records CRUD routes for teachers
    Route::resource('students', StudentController::class);
    Route::resource('school-forms-10', SchoolForm10Controller::class);
    Route::get('school-forms-10/{student_id}', [SchoolForm10Controller::class, 'show'])->name('school-forms-10.show');
    Route::resource('year-levels', YearLevelController::class)->except(['index', 'create', 'edit', 'show']);
    Route::resource('subjects', SubjectController::class)->except(['index', 'create', 'edit', 'show']);
    Route::resource('quarters', QuarterController::class)->except(['index', 'create', 'edit', 'show']);
    Route::resource('school-years', SchoolYearController::class)->except(['index', 'create', 'edit', 'show']);
    Route::get('year-levels-subjects-school-years-quarters', [ConfigurationsController::class, 'index'])->name('year-levels-subjects-school-years-quarters');
    Route::resource('class-records', ClassRecordController::class);
    Route::resource('summary_quarterly_grades', SummaryQuarterlyGradesController::class);
    Route::post('students/import', [CsvImportExportController::class, 'import'])->name('students.import');
    Route::post('students/delete-selected', [CsvImportExportController::class, 'deleteSelected'])->name('students.delete-selected');
    Route::post('students/{student}/promote', [PromoteStudentController::class, 'promote'])->name('students.promote');
    Route::get('students/{student}/{yearLevel}/{schoolYear}', [StudentController::class, 'show'])->name('students.sf09');
    Route::resource('attendance-core-values', AttendanceCoreValuesController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('attendance-core-values/create/{enrollment}', [AttendanceCoreValuesController::class, 'create'])->name('attendance-core-values.create');
    Route::get('attendance-core-values/{attendanceCoreValue}/edit', [AttendanceCoreValuesController::class, 'edit'])->name('attendance-core-values.edit');
    Route::put('attendance-core-values/{attendanceCoreValue}',[AttendanceCoreValuesController::class, 'update'])->name('attendance-core-values.update');
});


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Teacher-only routes
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher', [TeachersController::class, 'index'])->name('teacher.dashboard');
    });
});

Route::middleware('auth')->group(function () {
    // Teacher-only routes
    Route::middleware(['role:teacher|admin'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
