<?php

namespace App\Modules\Service\Log\Actions;

use App\Modules\Common\Actions\Action;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateErrorLog extends Action
{
    public function handle(string $message, array $context = [], ?Throwable $e = null)
    {
        if (! empty($e)) {
            $context = array_merge([
                'exception' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ],
            ], $context);
        }

        Log::error($message, $context);
    }
}
