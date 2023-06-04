<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Endpoints\ActivateQuota;
use App\Modules\Quota\Endpoints\ListPricings;
use App\Modules\Quota\Endpoints\ListQuotas;
use Illuminate\Support\Facades\Route;

class QuotaRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::get('/pricings', ListPricings::class)->middleware('throttle:120,1');

            Route::group([
                'middleware' => ['auth'],
            ], function () {
                Route::get('/quotas', ListQuotas::class)->middleware('throttle:120,1');
                Route::post('/quotas/{quota}:activate', ActivateQuota::class)->middleware('throttle:10,1');
            });
        });
    }
}
