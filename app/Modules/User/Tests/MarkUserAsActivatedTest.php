<?php

namespace App\Modules\User\Tests;

use App\Modules\User\Enums\UserState;
use App\Modules\User\User;
use Tests\TestCase;

class MarkUserAsActivatedTest extends TestCase
{
    public function test_mark_user_as_activated()
    {
        $referrer = User::factory()->create();
        $user = User::factory()->create([
            'state' => UserState::UNACTIVATED,
        ]);

        $this->actingAs($user)
            ->postJson("/api/users/$user->id:activate", [
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
