<?php

namespace App\Modules\Admin\Middlewares;

class MustBeAdmin
{
    public function handle($request, $next)
    {
        if (! $request->user()->isAdmin()) {
//            abort(403);
        }

        return $next($request);
    }
}
