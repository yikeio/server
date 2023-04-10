<?php

namespace App\Modules\Payment\Enums;

enum PaymentState: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case EXPIRED = 'expired';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    public function display(): string
    {
        return match ($this) {
            self::PENDING => '待支付',
            self::PAID => '已支付',
            self::EXPIRED => '已过期',
        };
    }
}
