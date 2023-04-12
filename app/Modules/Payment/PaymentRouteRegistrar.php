<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Endpoints\CreatePayment;
use App\Modules\Payment\Endpoints\GetPayment;
use App\Modules\Payment\Endpoints\ProcessPayment;
use Illuminate\Support\Facades\Route;

class PaymentRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::any('/payments:process', ProcessPayment::class)->middleware('throttle:600,1');

            Route::group([
                'middleware' => ['auth'],
            ], function () {
                Route::get('/payments/{payment}', GetPayment::class)->middleware('throttle:120,1');
                Route::post('/payments', CreatePayment::class)->middleware('throttle:60,1');
            });
        });
    }
}
