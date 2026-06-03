<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageManager::class, function ($app) {
            return  ImageManager::usingDriver(GdDriver::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
