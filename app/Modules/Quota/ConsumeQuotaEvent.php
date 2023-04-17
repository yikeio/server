<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Enums\QuotaType;
use App\Modules\User\User;

interface ConsumeQuotaEvent
{
    public function getCreator(): User;

    public function getQuotaType(): QuotaType;

    public function getTokensCount(): int;

    public function getUsage(): array;
}
