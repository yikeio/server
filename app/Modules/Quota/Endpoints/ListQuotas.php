<?php

namespace App\Modules\Quota\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class ListQuotas extends Endpoint
{
    public function __invoke(Request $request)
    {
        return $request->user()->quotas()->orderByDesc('id')->get();
    }
}
