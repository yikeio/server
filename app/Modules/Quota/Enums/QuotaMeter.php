<?php

namespace App\Modules\Quota\Enums;

use App\Modules\Quota\Meters\TokenQuotaMeter;

enum QuotaMeter: string
{
    case TOKEN = 'token';

    public function resolve(): string
    {
        return match ($this) {
            self::TOKEN => TokenQuotaMeter::class,
        };
    }
}
