<?php

namespace App\Providers;

use App\Common\ProcessFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;


class ImportFileProvider extends ServiceProvider
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
        App::bind('processfile',function (){
            return new ProcessFile();
        });
    }
}
