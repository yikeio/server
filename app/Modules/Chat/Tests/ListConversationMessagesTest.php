<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\User\User;
use Tests\TestCase;

class ListConversationMessagesTest extends TestCase
{
    public function test_list_conversation_messages()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        Message::factory()->count(10)->create(['conversation_id' => $conversation->id]);

        $this->actingAs($user)
            ->getJson("/api/conversations/{$conversation->id}/messages")
            ->assertJsonCount(10, 'data')
            ->assertSuccessful();
    }
}
