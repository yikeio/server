<?php

namespace App\Modules\User\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class InvalidStateException extends Exception
{
    protected $context = [];

    public function __construct($message = 'Invalid state', $code = 0, Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);

        $this->context = $context;
    }

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

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'context' => $this->context,
        ], $this->code);
    }
}
