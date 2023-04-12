<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Security\Actions\CheckSize;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListUserChatConversations extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('get', $user);

        return $user->conversations()
            ->filter($request->query())
            ->paginate(CheckSize::run($request->query('per_page', 15)));
    }
}
