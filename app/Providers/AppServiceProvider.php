<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("LivrariaAquarela")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("LivrariaAquarela")->setRelease("1.0.0");


       
    }
}
