<?php

namespace App\Modules\Service\OAuthClient;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class OAuthClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('client', function (Application $app) {
            return new ClientManager($app);
        });
    }

    public function boot()
    {
        Request::macro('client', function () {
            return app('client')->getClient();
        });
    }
}
