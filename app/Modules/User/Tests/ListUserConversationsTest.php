<?php

namespace App\Modules\User\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class ListUserConversationsTest extends TestCase
{
    public function test_list_user_conversations()
    {
        /** @var User $user */
        $user = User::factory()->create();

        Conversation::factory()
            ->count(10)
            ->create([
                'creator_id' => $user->id,
            ]);

        $this->actingAs($user)
            ->getJson("/api/users/{$user->id}/conversations")
            ->assertJsonCount(10)
            ->assertSuccessful();
    }
}
