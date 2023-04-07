<?php

namespace App\Modules\Security\Middlewares;

use Closure;
use Illuminate\Http\Request;

class SetRequestAccept
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
