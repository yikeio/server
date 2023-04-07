<?php

namespace App\Modules\Security\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetRequestUser
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! empty($user)) {
            $request->setUserResolver(fn () => $user);
        }

        return $next($request);
    }
}
