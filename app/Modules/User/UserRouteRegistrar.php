<?php

namespace App\Modules\User;

use App\Modules\User\Endpoints\ActivateUser;
use App\Modules\User\Endpoints\GetStats;
use App\Modules\User\Endpoints\GetUser;
use App\Modules\User\Endpoints\GetUserQuota;
use App\Modules\User\Endpoints\ListReferrals;
use App\Modules\User\Endpoints\ListSettings;
use App\Modules\User\Endpoints\UpdateSetting;
use App\Modules\User\Endpoints\UpdateUser;
use Illuminate\Support\Facades\Route;

class UserRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api', 'auth'],
            'prefix' => 'api',
        ], function () {
            Route::get('/settings', ListSettings::class)->middleware('throttle:120,1');
            Route::get('/referrals', ListReferrals::class)->middleware('throttle:120,1');

            Route::put('/settings/{key}', UpdateSetting::class)->middleware('throttle:60,1');
            Route::post('/user:activate', ActivateUser::class)->middleware('throttle:60,1');

            Route::get('/user', GetUser::class)->middleware('throttle:120,1');
            Route::patch('/user', UpdateUser::class)->middleware('throttle:120,1');
            Route::get('/user:stats', GetStats::class)->middleware('throttle:60,1');
            Route::get('/quota', GetUserQuota::class)->middleware('throttle:120,1');
        });
    }
}
