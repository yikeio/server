<?php

namespace App\Modules\Sms;

use App\Modules\Sms\Endpoints\SendVerificationCode;
use Illuminate\Support\Facades\Route;

class SmsRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('/sms/verification-codes:send', SendVerificationCode::class);
        });
    }
}
