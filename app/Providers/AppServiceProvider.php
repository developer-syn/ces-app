<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SchoolInfo;

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
        View::composer('*', function ($view) {
            $schoolInfo = SchoolInfo::first(); // Adjust this to fetch the desired school info
            $view->with('schoolLogo', $schoolInfo->logo_path ?? null);
        });
    }

}
