<?php

namespace App\Modules\Reward;

use Illuminate\Support\ServiceProvider;

class RewardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        RewardRouteRegistrar::all();
    }
}
