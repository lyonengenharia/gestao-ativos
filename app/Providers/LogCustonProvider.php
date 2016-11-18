<?php

namespace App\Providers;

use App\Common\LogCustom;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LogCustonProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('logcustom',function (){
           return new LogCustom();
        });
    }
}
