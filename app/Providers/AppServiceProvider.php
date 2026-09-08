<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the DiscoverySource interface to the GooglePlacesSource implementation
        $this->app->bind(
            \App\Discovery\DiscoverySource::class,
            \App\Discovery\GooglePlacesSource::class
        );

        // Register the technology detector manager
        $this->app->bind(
            \App\Technology\TechnologyDetectorManager::class,
            function () {
                return new \App\Technology\TechnologyDetectorManager([
                    new \App\Technology\Detectors\WordPressDetector(),
                ]);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}