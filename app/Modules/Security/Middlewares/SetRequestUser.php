<?php

namespace App\Modules\Security\Middlewares;

use App\Modules\Security\Watchdog;
use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetRequestUser
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Watchdog $watchdog */
        $watchdog = app(Watchdog::class);

        if ($watchdog->hasValidAuthorizationHeader()) {
            /** @var User $user */
            $user = Auth::user();

            if (! empty($user)) {
                $request->setUserResolver(fn () => $user);
            }
        }

        return $next($request);
    }
}
