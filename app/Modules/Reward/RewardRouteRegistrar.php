<?php

namespace App\Modules\Reward;

use Illuminate\Support\Facades\Route;

class RewardRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api', 'auth'],
            'prefix' => 'api/',
        ], function () {
            Route::get('/rewards', Endpoints\ListRewards::class)->name('rewards.index');
        });
    }
}
