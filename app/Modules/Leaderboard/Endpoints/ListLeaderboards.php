<?php

namespace App\Modules\Leaderboard\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ListLeaderboards extends Endpoint
{
    public function __invoke(Request $request): Collection|array
    {
        return User::query()
            ->where('is_admin', false)
            ->where('referrals_count', '>', 0)
            ->orderByDesc('referrals_count')
            ->take(100)
            ->get()
            ->transform(function (User $user) {
                return $user->onlySafeFields();
            });
    }
}
