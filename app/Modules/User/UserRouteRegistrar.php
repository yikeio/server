<?php

namespace App\Modules\User;

use App\Modules\User\Endpoints\ActivateUser;
use App\Modules\User\Endpoints\GetUser;
use App\Modules\User\Endpoints\GetUserQuota;
use App\Modules\User\Endpoints\ListPayments;
use App\Modules\User\Endpoints\ListReferrals;
use App\Modules\User\Endpoints\ListSettings;
use App\Modules\User\Endpoints\ListUserChatConversations;
use App\Modules\User\Endpoints\ListUserPayments;
use App\Modules\User\Endpoints\ListUserQuotas;
use App\Modules\User\Endpoints\ListUserReferrals;
use App\Modules\User\Endpoints\ListUserSettings;
use App\Modules\User\Endpoints\UpdateSetting;
use App\Modules\User\Endpoints\UpdateUserSetting;
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
            Route::get('/payments', ListPayments::class)->middleware('throttle:120,1');

            Route::put('/settings/{key}', UpdateSetting::class)->middleware('throttle:60,1');
            Route::post('/users/{user}:activate', ActivateUser::class)->middleware('throttle:60,1');

            Route::get('/user', GetUser::class)->middleware('throttle:120,1');
            Route::get('/users/{user}/quotas', ListUserQuotas::class)->middleware('throttle:120,1');
            Route::get('/users/{user}/quota', GetUserQuota::class)->middleware('throttle:120,1');

            // @todo: remove these all endpoint
            Route::get('/users/{user}/payments', ListUserPayments::class)->middleware('throttle:120,1');
            Route::put('/users/{user}/settings/{key}', UpdateUserSetting::class)->middleware('throttle:60,1');
            Route::get('/users/{user}/settings', ListUserSettings::class)->middleware('throttle:120,1');
            Route::get('/users/{user}/referrals', ListUserReferrals::class)->middleware('throttle:120,1');
            Route::get('/users/{user}/chat/conversations', ListUserChatConversations::class)->middleware('throttle:120,1');
        });
    }
}
