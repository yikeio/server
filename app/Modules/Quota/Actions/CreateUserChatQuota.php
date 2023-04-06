<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Quota\Quota;
use App\Modules\User\User;

class CreateUserChatQuota extends Action
{
    public function handle(User $user, int $tokensCount, int $days): Quota
    {
        $quota = new Quota();
        $quota->is_available = true;
        $quota->type = QuotaType::CHAT;

        $meter = $quota->getMeter();
        $meter->recharge($tokensCount);

        $quota->usage = $meter->getUsage();
        $quota->expired_at = now()->addDays($days);

        $user->quotas()->save($quota);

        return $quota;
    }
}
