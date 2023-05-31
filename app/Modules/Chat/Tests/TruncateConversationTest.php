<?php

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\User\User;
use Tests\TestCase;

class TruncateConversationTest extends TestCase
{
    public function test_truncate_conversation_messages()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        Message::factory()->count(10)->create(['conversation_id' => $conversation->id]);

        $this->actingAs($user)
            ->getJson("/api/chat/conversations/{$conversation->id}/messages")
            ->assertJsonCount(10, 'data')
            ->assertSuccessful();

        // truncate
        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}:truncate")
            ->assertNoContent();

        // check
        $this->actingAs($user)
            ->getJson("/api/chat/conversations/{$conversation->id}/messages")
            ->assertJsonCount(0, 'data')
            ->assertSuccessful();
    }
}
