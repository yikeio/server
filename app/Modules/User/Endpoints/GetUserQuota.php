<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Http\Request;

class GetUserQuota extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('get', $user);

        $quota = $user->getAvailableQuota();

        if (! empty($quota)) {
            return $quota;
        }

        return $user->quotas()->orderByDesc('id')->first();
    }
}
