<?php

namespace App\Modules\Quota\Listeners;

use App\Modules\Quota\Actions\GrantUserQuota;
use App\Modules\Quota\Enums\QuotaMeter;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\User\Events\UserCreated;

class GrantFreeQuotas
{
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $default = config('quota.defaults.chat');

        GrantUserQuota::run($user, [
            'quota_type' => QuotaType::CHAT,
            'quota_meter' => QuotaMeter::TOKEN,
            'tokens_count' => $default['tokens_count'] ?? 1000,
            'days' => $default['days'] ?? 1,
        ]);
    }
}
