<?php

namespace App\Modules\Quota\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Enums\QuotaState;
use App\Modules\Quota\Quota;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ActivateQuota extends Endpoint
{
    public function __invoke(Request $request, Quota $quota): Quota
    {
        $this->authorize('activate', $quota);

        if (! $quota->state->isPending()) {
            abort(403, '配额状态已无法变更');
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->getUsingQuota()) {
            abort(403, '您已有正在使用的配额');
        }

        $quota->state = QuotaState::USING;
        $quota->save();

        return $quota;
    }
}
