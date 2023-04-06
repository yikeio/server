<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Listeners\GrantFreeQuotas;
use App\Modules\User\Events\UserCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class QuotaEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            GrantFreeQuotas::class,
        ],
    ];
}
