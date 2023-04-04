<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test_create_user()
    {
        $this->postJson('/api/users', [
            'phone' => '+86:18600000000',
        ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => '用户-0000',
            ])
            ->assertJsonIsObject('user')
            ->assertJsonIsObject('token')
            ->assertJsonFragment([
                'referrer_id' => '0',
                'root_referrer_id' => '0',
                'referrer_path' => null,
            ]);
    }

    public function test_create_user_with_referral_code()
    {
        $referrer = User::factory()->create();

        $this->postJson('/api/users', [
            'phone' => '+86:18600000000',
            'referral_code' => $referrer->referral_code,
        ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => '用户-0000',
            ])
            ->assertJsonIsObject('user')
            ->assertJsonIsObject('token')
            ->assertJsonFragment([
                'referrer_id' => $referrer->id,
                'root_referrer_id' => $referrer->id,
                'referrer_path' => $referrer->id,
            ]);
    }
}
