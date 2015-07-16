<?php

namespace JasonHerndon\WPToLaravel;

use Illuminate\Support\ServiceProvider;

class WPToLaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish a configuration file
        $this->publishes([__DIR__.'/../config/config.php' => config_path('wptolaravel.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package configuration file with the application's copy
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'wptolaravel');
    }
}
