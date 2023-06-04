<?php

namespace App\Modules\Quota\Middlewares;

use App\Modules\Quota\Exceptions\QuotaException;
use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;

class CheckQuota
{
    public function handle(Request $request, Closure $next, string $type)
    {
        /** @var User $user */
        $user = $request->user();

        if (! empty($user)) {
            $quota = $user->getUsingQuota();

            if (empty($quota)) {
                throw QuotaException::quotaNotEnough('您没有可用的配额');
            }
        }

        return $next($request);
    }
}
