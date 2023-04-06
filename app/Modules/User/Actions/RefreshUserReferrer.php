<?php

namespace App\Modules\User\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\User\User;

class RefreshUserReferrer extends Action
{
    public function handle(User $user, User $referrer): User
    {
        $user->referrer_id = $referrer->id;
        $user->root_referrer_id = $referrer->root_referrer_id ?: $referrer->id;

        if (! empty($referrer->referrer_path)) {
            $user->referrer_path = sprintf('%s-%s', $referrer->referrer_path, $referrer->id);
        } else {
            $user->referrer_path = $referrer->id;
        }

        $user->level = $referrer->level + 1;

        $user->timestamps = false;
        $user->save();

        $referrer->referrals_count = $referrer->referrals()->count();
        $referrer->timestamps = false;
        $referrer->save();

        return $user;
    }
}
