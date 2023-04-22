<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListUserReferrals extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('get', $user);

        return $user->referrals()->get();
    }
}