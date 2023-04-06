<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Quota\Quota;
use App\Modules\User\User;
use Illuminate\Support\Facades\Cache;

class ConsumeUserQuota extends Action
{
    public function handle(User $user, QuotaType $type, int $tokensCount): Quota
    {
        $quota = $user->getQuota($type);

        return Cache::lock("user_{$user->id}_quota_{$quota->id}", 10)
            ->block(5, function () use ($quota, $tokensCount) {
                $meter = $quota->getMeter();
                $meter->consume($tokensCount);

                $quota->usage = $meter->getUsage();
                $quota->is_available = $meter->getBalance() > 0;
                $quota->save();

                return $quota;
            });
    }
}
