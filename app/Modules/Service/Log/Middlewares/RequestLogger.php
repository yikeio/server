<?php

namespace App\Modules\Service\Log\Middlewares;

use App\Modules\Service\Log\LogChannel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        Log::channel(LogChannel::REQUEST->value)->info('request logger middleware', [
            'status_code' => $response->getStatusCode(),
            'response' => $response->isSuccessful() ? [] : json_decode($response->getContent(), true),
        ]);
    }
}
