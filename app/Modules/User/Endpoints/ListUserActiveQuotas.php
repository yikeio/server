<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListUserActiveQuotas extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        if ($user->isNot($request->user())) {
            abort(403);
        }

        return [
            'chat' => $user->getQuota(QuotaType::CHAT),
        ];
    }
}
