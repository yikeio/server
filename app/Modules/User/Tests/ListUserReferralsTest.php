<?php

namespace App\Modules\User\Tests;

use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\User;
use Tests\TestCase;

class ListUserReferralsTest extends TestCase
{
    public function test_list_user_referrals()
    {
        $user = User::factory()->create();

        $referral = User::factory()->create();

        RefreshUserReferrer::run($referral, $user);

        $this->actingAs($user)
            ->getJson('/api/referrals')
            ->assertJsonCount(1);
    }
}
