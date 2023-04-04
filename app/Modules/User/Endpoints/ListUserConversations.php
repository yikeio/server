<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListUserConversations extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        if ($user->isNot($request->user())) {
            abort(403);
        }

        return $user->conversations()->orderByDesc('id')->get();
    }
}
