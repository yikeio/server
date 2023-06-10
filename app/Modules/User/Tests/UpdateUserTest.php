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
}
