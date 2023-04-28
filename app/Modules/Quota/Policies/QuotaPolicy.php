<?php

namespace App\Modules\Quota\Policies;

use App\Modules\Quota\Quota;
use App\Modules\User\User;

class QuotaPolicy
{
    public function activate(User $user, Quota $quota): bool
    {
        return $user->is($quota->user);
    }
}
