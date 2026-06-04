<?php

namespace App\Providers;

use App\Policies\CandidatePolicy;
use App\Policies\EmployerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageManager::class, function ($app) {
            return ImageManager::usingDriver(GdDriver::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view-candidate-profile', [CandidatePolicy::class, 'view']);
        Gate::define('view-candidate-profile-for-employer', [CandidatePolicy::class, 'viewForEmployer']);
        Gate::define('update-candidate-profile', [CandidatePolicy::class, 'update']);
        Gate::define('view-employer-profile', [EmployerPolicy::class, 'view']);
        Gate::define('update-employer-profile', [EmployerPolicy::class, 'update']);
    }
}
