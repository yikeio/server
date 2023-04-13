<?php

namespace App\Modules\Security;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Watchdog::class, function (Application $app) {
            return new Watchdog($app);
        });
    }
}
