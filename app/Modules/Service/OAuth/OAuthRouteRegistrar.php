<?php

namespace App\Modules\Service\OAuth;

use App\Modules\Service\OAuth\Endpoints\CreateTokenViaSms;
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
