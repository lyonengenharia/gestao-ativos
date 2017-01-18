<?php

namespace App\Providers;


use App\Facades\Ldap;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LdapFunctionsProvider extends ServiceProvider
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
        App::bind('ldap',function (){
           return new \App\Common\Ldap();
        });
    }
}
