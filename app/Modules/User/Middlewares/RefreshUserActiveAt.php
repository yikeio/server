<?php

namespace App\Modules\User\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshUserActiveAt
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return;
        }

        $user->timestamps = false;

        if (! $user->first_active_at) {
            $user->first_active_at = now();
        }

        $user->last_active_at = now();

        $user->saveQuietly();
    }
}
