<?php

namespace App\Modules\Auth;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ClientManager::class, function (Application $app) {
            return new ClientManager($app);
        });
    }

    public function boot()
    {
        AuthRouteRegistrar::all();

        Request::macro('client', function () {
            return app(ClientManager::class)->getClient();
        });
    }
}
