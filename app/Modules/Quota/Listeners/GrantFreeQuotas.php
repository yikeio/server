<?php

namespace App\Modules\Quota\Listeners;

use App\Modules\Quota\Actions\GrantUserQuota;
use App\Modules\User\Events\UserActivated;

class GrantFreeQuotas
{
    public function handle(UserActivated $event)
    {
        $user = $event->user;

        $default = config('quota.defaults.chat');

        GrantUserQuota::run($user, [
            'tokens_count' => $default['tokens_count'] ?? 1000,
            'days' => $default['days'] ?? 1,
        ]);
    }
}
