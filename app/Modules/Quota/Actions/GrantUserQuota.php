<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\Enums\QuotaState;
use App\Modules\Quota\Quota;
use App\Modules\User\User;

class GrantUserQuota extends Action
{
    public function handle(User $user, array $parameters): Quota
    {
        $quota = new Quota();
        $quota->state = QuotaState::USING;
        $quota->tokens_count = $parameters['tokens_count'];

        if (! empty($parameters['days'])) {
            $quota->expired_at = now()->addDays($parameters['days']);
        }

        $user->quotas()->save($quota);

        return $quota;
    }
}
