<?php

namespace App\Modules\Quota;

use App\Modules\User\User;

interface ConsumeQuotaEvent
{
    public function getCreator(): User;

    public function getQuota(): Quota;

    public function getTokensCount(): int;

    public function getUsage(): array;
}
