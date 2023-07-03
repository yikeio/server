<?php

namespace App\Modules\User\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\User\User;
use Illuminate\Support\Facades\Cache;

class GetUserRewardsTotal extends Action
{
    public function handle(User $user)
    {
        return Cache::remember("user:{$user->id}:rewards_total", 60, function() use ($user) {
            return $user->rewards()->sum('amount');
        });
    }
}
