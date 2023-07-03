<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class ListReferrals extends Endpoint
{
    public function __invoke(Request $request)
    {
        return $request->user()->referrals()->latest()->paginate(15);
    }
}
