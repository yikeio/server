<?php

namespace App\Modules\User\Endpoints;

use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\Enums\UserState;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UpdateUser
{
    public function __invoke(UpdateUserRequest $request): User
    {
        $user = $request->user();

        $user->update(Arr::except($request->validated(), ['referral_code']));

        if ($request->has('referral_code') && ! $user->referrer_id) {
            $referrer = User::query()
                ->where('referral_code', Str::lower($request->input('referral_code')))
                ->where('state', UserState::ACTIVATED)
                ->first();

            if (! empty($referrer) && ! $referrer->is($user)) {
                RefreshUserReferrer::run($user, $referrer);
            }
        }

        return $user->refresh();
    }
}
