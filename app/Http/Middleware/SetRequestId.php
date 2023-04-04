<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetRequestId
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = Str::uuid()->toString();

        Log::shareContext(['request_id' => $requestId]);

        return tap($next($request), fn ($response) => $response->headers->set('X-Request-Id', $requestId));
    }
}
