<?php

namespace App\Modules\Quota\Listeners;

use App\Modules\Quota\Actions\CreateUserChatQuota;
use App\Modules\User\Events\UserCreated;

class GrantFreeQuotas
{
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $chat = config('quota.defaults.chat');

        CreateUserChatQuota::run($user, $chat['tokens_count'], $chat['days']);
    }
}
