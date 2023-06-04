<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class GetUserQuota extends Endpoint
{
    public function __invoke(Request $request)
    {
        $quota = $request->user()->getAvailableQuota();

        if (! empty($quota)) {
            return $quota;
        }

        return $request->user()->quotas()->orderByDesc('id')->firstOrNew();
    }
}
