<?php

namespace App\Modules\Admin;

use App\Modules\Admin\Endpoints;
use App\Modules\Admin\Middlewares\MustBeAdmin;
use Illuminate\Support\Facades\Route;

class AdminRouteRegistrar
{
    static function all()
    {
        Route::group([
//            'middleware' => ['api', 'auth:api', MustBeAdmin::class],
            'middleware' => ['api'],
            'prefix' => 'api/admin/',
            'as' => 'admin.',
        ], function () {
            Route::get('prompts', Endpoints\ListPrompts::class);
            Route::post('prompts', Endpoints\CreatePrompt::class);
            Route::get('users', Endpoints\ListUsers::class);
            Route::get('payments', Endpoints\ListPayments::class);
            Route::get('gift-cards', Endpoints\ListGiftCards::class);
            Route::get('conversations', Endpoints\ListConversations::class);

            Route::patch('users/{user}', Endpoints\UpdateUser::class);
            Route::delete('users/{user}', Endpoints\DeleteUser::class);

            Route::patch('prompts/{prompt}', Endpoints\UpdatePrompt::class);
            Route::delete('prompts/{prompt}', Endpoints\DeletePrompt::class);
        });
    }
}
