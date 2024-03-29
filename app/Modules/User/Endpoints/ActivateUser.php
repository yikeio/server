<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\Enums\UserState;
use App\Modules\User\Events\UserActivated;
use App\Modules\User\Requests\ActivateUserRequest;
use App\Modules\User\User;
use Illuminate\Support\Str;

class ActivateUser extends Endpoint
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(ActivateUserRequest $request)
    {
        $user = $request->user();

        if (! $user->state->unactivated()) {
            abort(403, '您已经激活过了');
        }

        $referrer = User::query()
            ->where('referral_code', Str::lower($request->input('referral_code')))
            ->where('state', UserState::ACTIVATED)
            ->first();

        if (empty($referrer) || $referrer->is($user)) {
            abort(422, '邀请码无效');
        }

        RefreshUserReferrer::run($user, $referrer);

        $user->state = UserState::ACTIVATED;
        $user->save();

        event(new UserActivated($user));

        return $user;
    }
}
