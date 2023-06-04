<?php

namespace App\Modules\Common;

use Illuminate\Http\JsonResponse;
use Throwable;

trait RenderWithContext
{
    protected array $context = [];

    public function __construct(string $message = 'Server error', int $code = 0, Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);

        $this->context = $context;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'context' => $this->context,
        ], $this->code);
    }
}
