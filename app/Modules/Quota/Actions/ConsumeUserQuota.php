<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Quota\MeterManager;
use App\Modules\Quota\Quota;
use App\Modules\User\User;
use Illuminate\Support\Facades\Cache;

class ConsumeUserQuota extends Action
{
    public function handle(User $user, QuotaType $type, int $tokensCount): Quota
    {
        /** @var Quota $quota */
        $quota = $user->getQuota($type);

        $key = "user_{$user->id}_consume_quota_{$quota->id}";

        return Cache::lock($key, 10)
            ->block(5, function () use ($quota, $tokensCount) {
                /** @var MeterManager $meterManager */
                $meterManager = app(MeterManager::class);

                $meter = $meterManager->get($quota->meter);

                $meter->setUsage($quota->usage ?? []);
                $meter->consume($tokensCount);

                $quota->usage = $meter->getUsage();
                $quota->is_available = $meter->getBalance() > 0;
                $quota->save();

                return $quota;
            });
    }
}
