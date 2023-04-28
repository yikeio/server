<?php

namespace App\Modules\Security\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis as ThrottleRequests;
use Illuminate\Routing\Route;

class ThrottleRequestsWithRedis extends ThrottleRequests
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        return $next($request);
    }

    protected function resolveRequestSignature($request): string
    {
        /** @var User $user */
        $user = $request->user();

        $key = sha1($user?->getKey() ?? $request->ip());

        return sprintf('throttle_%s_%s', $key, $this->getRouteHashValue($request->route()));
    }

    protected function getRouteHashValue(Route $route): string
    {
        return sha1(implode('|', $route->methods()).':'.$route->uri());
    }
}
