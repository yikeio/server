<?php

namespace App\Modules\Service\Sms;

use App\Modules\Service\Sms\Endpoints\SendVerificationCode;
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
