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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
