<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class ActivateUserTest extends TestCase
{
    public function test_mark_user_as_activated()
    {
        $referrer = User::factory()->create();
        $user = User::factory()->unactivated()->create();

        $this->actingAs($user)
            ->postJson('/api/user:activate', [
                'referral_code' => $referrer->referral_code,
            ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'referrer_id' => $referrer->id,
                'root_referrer_id' => $referrer->id,
                'referrer_path' => $referrer->id,
            ]);
    }
}
