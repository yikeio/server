<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class DeleteConversationTest extends TestCase
{
    public function test_delete_conversation()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        $this->actingAs($user)
            ->deleteJson("/api/chat/conversations/{$conversation->id}")
            ->assertSuccessful();
    }
}
