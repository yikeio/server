<?php

namespace App\Modules\Leaderboard\Tests;

use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\User;
use Tests\TestCase;

class ListLeaderboardsTest extends TestCase
{
    public function test_list_leaderboards()
    {
        $user = User::factory()->create();

        $referral = User::factory()->create();

        RefreshUserReferrer::run($referral, $user);

        $this->actingAs($user)
            ->getJson('/api/leaderboards')
            ->assertSuccessful()
            ->assertJsonStructure([
                'referrers',
            ]);
    }
}
