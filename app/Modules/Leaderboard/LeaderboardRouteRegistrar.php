<?php

namespace App\Modules\Leaderboard;

use App\Modules\Leaderboard\Endpoints\ListLeaderboards;
use Illuminate\Support\Facades\Route;

class LeaderboardRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api', 'auth'],
            'prefix' => 'api',
        ], function () {
            Route::get('/leaderboards', ListLeaderboards::class);
        });
    }
}
