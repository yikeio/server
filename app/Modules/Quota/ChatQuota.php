<?php

namespace App\Modules\Quota;

class ChatQuota
{
    public function __construct(protected array $context)
    {

    }

    public function toArray(): array
    {
        return [
            'total_tokens',
            'used_tokens',
            'available_tokens',
            'model',
        ];
    }
}
