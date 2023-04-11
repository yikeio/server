<?php

namespace App\Modules\User\Enums;

enum UserState: string
{
    case ACTIVATED = 'activated';
    case UNACTIVATED = 'unactivated';
    case BANNED = 'banned';

    public function activated(): bool
    {
        return $this === self::ACTIVATED;
    }

    public function unactivated(): bool
    {
        return $this === self::UNACTIVATED;
    }

    public function banned(): bool
    {
        return $this === self::BANNED;
    }
}
