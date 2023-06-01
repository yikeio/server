<?php

namespace App\Modules\Quota\Enums;

enum QuotaState: string
{
    case PENDING = 'pending';
    case USING = 'using';
    case USED = 'used';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => '待使用',
            self::USING => '使用中',
            self::USED => '已使用',
            self::EXPIRED => '已过期',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isUsing(): bool
    {
        return $this === self::USING;
    }

    public function isUsed(): bool
    {
        return $this === self::USED;
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }
}
