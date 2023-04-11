<?php

namespace App\Modules\Auth;

use App\Modules\Auth\Endpoints\CreateTokenViaSms;
use Illuminate\Support\Facades\Route;

class AuthRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('/auth/tokens:via-sms', CreateTokenViaSms::class)->middleware('throttle:10,1');
        });
    }
}
