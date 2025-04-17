<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SchoolInfo;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::model('enrollment', StudentEnrollment::class);
        Route::model('attendance_core_value', \App\Models\AttendanceCoreValue::class);

        View::composer('*', function ($view) {
            $schoolInfo = SchoolInfo::first(); // Adjust this to fetch the desired school info
            $view->with('schoolLogo', $schoolInfo->logo_path ?? null);
        });
    }

}
