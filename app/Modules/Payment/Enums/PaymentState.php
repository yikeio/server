<?php

namespace App\Modules\Payment\Enums;

enum PaymentState: string
{
    case PENDING = 'pending';
    case PAID = 'paid';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function display(): string
    {
        return match ($this) {
            self::PENDING => '待支付',
            self::PAID => '已支付',
        };
    }
}
