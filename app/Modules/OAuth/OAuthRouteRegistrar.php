<?php

namespace App\Modules\OAuth;

use App\Modules\OAuth\Endpoints\CreateTokenViaSms;
use Illuminate\Support\Facades\Route;

class OAuthRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('/oauth/tokens:via-sms', CreateTokenViaSms::class)->middleware('throttle:10,1');
        });
    }
}
