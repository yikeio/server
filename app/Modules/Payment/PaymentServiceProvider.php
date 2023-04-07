<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Gateways\GatewayInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GatewayManager::class, function (Application $app) {
            return new GatewayManager(config('payment.gateways'));
        });

        $this->app->singleton(GatewayInterface::class, function (Application $app) {
            return $app->make(GatewayManager::class)->get(config('payment.gateway'));
        });
    }

    public function boot()
    {
        PaymentRouteRegistrar::all();
    }
}
