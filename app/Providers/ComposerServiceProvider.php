<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Enrollment;
use App\Models\AssessmentApplication;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.navigation', function ($view) {
            $newEnrollmentsCount = Enrollment::where('viewed', false)->count();
            $newAssessmentsCount = AssessmentApplication::where('viewed', false)->count();
            $newApplicationsCount = $newEnrollmentsCount + $newAssessmentsCount;

            $view->with('newApplicationsCount', $newApplicationsCount);
        });
    }
}
