<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\MeterManager;
use App\Modules\Quota\Quota;
use App\Modules\User\User;

class GrantUserQuota extends Action
{
    public function handle(User $user, array $parameters): Quota
    {
        $quota = new Quota();
        $quota->is_available = true;
        $quota->type = $parameters['quota_type'];
        $quota->meter = $parameters['quota_meter'];

        /** @var MeterManager $meterManager */
        $meterManager = app(MeterManager::class);

        $meter = $meterManager->get($parameters['quota_meter']);

        $meter->setUsage($quota->usage ?? []);
        $meter->recharge($parameters['tokens_count']);

        $quota->usage = $meter->getUsage();

        if (! empty($parameters['days'])) {
            $quota->expired_at = now()->addDays($parameters['days']);
        }

        $user->quotas()->save($quota);

        return $quota;
    }
}
