<?php

namespace App\Modules\User;

use App\Modules\User\Events\UserCreated;
use App\Modules\User\Listeners\CreateUserSettings;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class UserEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            CreateUserSettings::class,
        ],
    ];
}
