<?php

namespace App\Modules\User;

use App\Modules\User\Endpoints\CreateUser;
use Illuminate\Support\Facades\Route;

class UserRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('users', CreateUser::class);
        });
    }
}
