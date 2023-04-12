<?php

namespace App\Modules\User\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserState
{
    protected array $except = [
        '/api/users/*:activate',
        '/api/user',
    ];

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
            abort(403, '您的账号已被禁用');
        }

        if ($user->state?->unactivated()) {
            abort(403, '您的账号未激活');
        }

        abort(403, '您的账号状态异常');
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
