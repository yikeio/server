<?php

namespace App\Modules\User;

use App\Modules\User\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::policy(User::class, UserPolicy::class);

        UserRouteRegistrar::all();
    }
}
