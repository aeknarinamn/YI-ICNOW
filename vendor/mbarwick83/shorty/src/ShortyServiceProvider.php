<?php

namespace Mbarwick83\Shorty;

use Illuminate\Support\ServiceProvider;

class ShortyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/shorty.php' => config_path('shorty.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Mbarwick83\Shorty\Shorty',function($app){
            return new Shorty($app);
        });
    }
}