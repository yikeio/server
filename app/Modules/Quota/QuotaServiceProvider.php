<?php

namespace App\Modules\Quota;

use Illuminate\Support\ServiceProvider;

class QuotaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MeterManager::class, function () {
            return new MeterManager();
        });
    }

    public function boot()
    {
        QuotaRouteRegistrar::all();
    }
}
