<?php

namespace App\Modules\Payment\Enums;

use App\Modules\Payment\Gateways\Payjs\Gateway as Payjs;

enum Gateway: string
{
    case PAYJS = 'payjs';

    public function resolve(): string
    {
        return match ($this) {
            self::PAYJS => Payjs::class,
        };
    }
}
