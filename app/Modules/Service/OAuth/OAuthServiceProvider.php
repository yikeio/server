<?php

namespace App\Modules\Service\OAuth;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ClientManager::class, function (Application $app) {
            return new ClientManager($app);
        });
    }

    public function boot()
    {
        OAuthRouteRegistrar::all();

        Request::macro('client', function () {
            return app(ClientManager::class)->getClient();
        });
    }
}
