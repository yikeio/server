<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Endpoints\ListPayableQuotas;
use Illuminate\Support\Facades\Route;

class QuotaRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::get('/payable-quotas', ListPayableQuotas::class);
        });
    }
}
