<?php

namespace App\Modules\Payment\Enums;

use App\Modules\Payment\Gateways\Payjs\Gateway as Payjs;

enum Gateway: string
{
    case PAYJS = 'payjs';
    case GIFT_CARD = 'gift_card';

    public function resolve(): string
    {
        return match ($this) {
            self::PAYJS => Payjs::class,
        };
    }
}
