<?php

namespace App\Modules\Quota\Exceptions;

use App\Modules\Common\RenderWithContext;
use Exception;

class QuotaException extends Exception
{
    use RenderWithContext;

    public static function quotaNotEnough(string $message = '配额不足'): self
    {
        return new static($message, 403, null, ['state' => 'quota_not_enough']);
    }
}
