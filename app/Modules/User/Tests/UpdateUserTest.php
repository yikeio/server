<?php

use App\Modules\User\User;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    public function test_user_can_update_self()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'avatar' => '/path/to/avatar.jpg',
        ]);

        $this->actingAs($user)
            ->patchJson('/api/user', [
                'name' => 'overtrue',
                'avatar' => '/path/to/overtrue.png',
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'overtrue',
            'avatar' => '/path/to/overtrue.png',
        ]);
    }

    public function test_user_can_set_referrer()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'avatar' => '/path/to/avatar.jpg',
        ]);

        $referrer = User::factory()->activated()->create([
            'name' => 'John Doe',
            'avatar' => '/path/to/avatar.jpg',
            'referral_code' => 'abc123',
        ]);

        // 没有邀请人
        $this->assertNull($user->referrer_id);

        // 设置邀请人
        $this->actingAs($user)
            ->patchJson('/api/user', [
                'referral_code' => $referrer->referral_code,
            ])
            ->assertSuccessful();

        // 邀请人设置成功
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'referrer_id' => $referrer->id,
        ]);

        // 重复设置不会覆盖
        $referrer2 = User::factory()->create([
            'name' => 'John Doe',
            'avatar' => '/path/to/avatar.jpg',
            'referral_code' => 'abc123',
        ]);

        $this->actingAs($user)
            ->patchJson('/api/user', [
                'referral_code' => $referrer2->referral_code,
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'referrer_id' => $referrer->id, // not changed
        ]);
    }
}
