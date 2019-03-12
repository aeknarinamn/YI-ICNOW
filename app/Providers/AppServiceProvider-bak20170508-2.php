<?php

namespace YellowProject\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       $this->app->singleton("fwdccf", "TH_CO_FWD\CCF\FWDCCFController" );
        $aws = $this->app->make('aws');
        // $key = 'Asdfghjklqwertya';
        // dd($key);
        $key = app()["fwdccf"]->init('YellowProject', env('APP_KEY'), $aws);
        \Session::put('FwdKey', $key);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
