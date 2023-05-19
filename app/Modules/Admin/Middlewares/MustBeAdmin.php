<?php

namespace App\Modules\Admin\Middlewares;

class MustBeAdmin
{
    public function handle($request, $next)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
