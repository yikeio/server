<?php

namespace App\Modules\User\Exceptions;

use App\Modules\Common\RenderWithContext;
use Exception;

class InvalidStateException extends Exception
{
    use RenderWithContext;

    public static function banned(string $message = '您的账号已被禁用'): static
    {
        return new static($message, 403, null, ['state' => 'banned']);
    }

    public static function unactivated(string $message = '您的账号未激活'): static
    {
        return new static($message, 403, null, ['state' => 'unactivated']);
    }

    public static function invalid(string $message = '您的账号状态异常'): static
    {
        return new static($message, 403, null, ['state' => 'invalid']);
    }
}
