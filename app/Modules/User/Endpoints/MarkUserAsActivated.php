<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\Enums\UserState;
use App\Modules\User\Requests\MarkUserAsActiveRequest;
use App\Modules\User\User;

class MarkUserAsActivated extends Endpoint
{
    public function __invoke(MarkUserAsActiveRequest $request, User $user)
    {
        if ($user->isNot($request->user())) {
            abort(403);
        }

        if (! $user->state->unactivated()) {
            abort(403, '您已经激活过了');
        }

        $referrer = User::query()
            ->where('referral_code', $request->input('referral_code'))
            ->first();

        if (empty($referrer)) {
            abort(422, '邀请码无效');
        }

        RefreshUserReferrer::run($user, $referrer);

        $user->state = UserState::ACTIVATED;
        $user->save();

        return $user;
    }
}
