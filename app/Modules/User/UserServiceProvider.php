<?php

namespace App\Modules\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        UserRouteRegistrar::all();
    }
}
