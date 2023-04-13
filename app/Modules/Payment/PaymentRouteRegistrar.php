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
            Route::any('/payments:process', ProcessPayment::class);

            Route::group([
                'middleware' => ['auth', 'limiter'],
            ], function () {
                Route::post('/payments', CreatePayment::class)->middleware('throttle:10,1');
                Route::get('/payments/{payment}', GetPayment::class);
            });
        });
    }
}
