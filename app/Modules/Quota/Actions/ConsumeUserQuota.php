<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\MeterManager;
use App\Modules\Quota\Quota;
use Illuminate\Support\Facades\Cache;

class ConsumeUserQuota extends Action
{
    public function handle(Quota $quota, int $tokensCount): Quota
    {
        $key = "user_{$quota->user->id}_consume_quota_{$quota->id}";

        return Cache::lock($key, 10)
            ->block(5, function () use ($quota, $tokensCount) {
                // 拿到锁之后刷新一次数据，防止出现数据不一致的情况
                $quota = $quota->refresh();

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
