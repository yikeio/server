<?php

namespace App\Modules\User;

use App\Modules\User\Endpoints\CreateUser;
use App\Modules\User\Endpoints\GetUser;
use App\Modules\User\Endpoints\ListUserActiveQuotas;
use App\Modules\User\Endpoints\ListUserConversations;
use App\Modules\User\Endpoints\ListUserQuotas;
use Illuminate\Support\Facades\Route;

class UserRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('/users', CreateUser::class);

            Route::group([
                'middleware' => ['auth'],
            ], function () {
                Route::get('/user', GetUser::class);
                Route::get('/users/{user}/conversations', ListUserConversations::class);
                Route::get('/users/{user}/quotas', ListUserQuotas::class);
                Route::get('/users/{user}/active-quotas', ListUserActiveQuotas::class);
            });
        });
    }
}
