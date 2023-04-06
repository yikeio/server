<?php

namespace App\Modules\Quota\Enums;

use App\Modules\Quota\Meters\ChatMeter;
use App\Modules\Quota\Meters\Meter;

enum QuotaType: string
{
    case CHAT = 'chat';

    public function getMeter(array $usage = []): Meter
    {
        return match ($this) {
            self::CHAT => new ChatMeter($usage),
        };
    }
}
