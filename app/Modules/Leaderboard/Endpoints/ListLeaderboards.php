<?php

namespace App\Modules\Leaderboard\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListLeaderboards extends Endpoint
{
    public function __invoke(Request $request)
    {
        $referrers = User::query()
            ->where('referrals_count', '>', 0)
            ->orderByDesc('referrals_count')
            ->limit(1000)
            ->get(['name', 'referrals_count']);

        return [
            'referrers' => $referrers,
        ];
    }
}
