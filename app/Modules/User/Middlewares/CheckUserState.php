<?php

namespace App\Modules\User\Middlewares;

use App\Modules\User\Exceptions\InvalidStateException;
use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserState
{
    protected array $except = [
        '/api/user:activate',
        '/api/user',
    ];

    /**
     * @throws \App\Modules\User\Exceptions\InvalidStateException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        /** @var User $user */
        $user = $request->user();

        if (empty($user) || $user->state?->activated()) {
            return $next($request);
        }

        if ($user->state?->banned()) {
            throw InvalidStateException::banned();
        }

        if ($user->state?->unactivated()) {
            throw InvalidStateException::unactivated();
        }

        throw InvalidStateException::invalid();
    }

    protected function inExceptArray($request): bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
