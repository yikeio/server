<?php

namespace App\Modules\Leaderboard;

use Illuminate\Support\ServiceProvider;

class LeaderboardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        LeaderboardRouteRegistrar::all();
    }
}
