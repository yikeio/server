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
            ->getJson("/api/chat/conversations/{$conversation->id}/messages")
            ->assertJsonCount(10, 'data')
            ->assertSuccessful();
    }

    public function test_message_item_will_has_liked_status()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        $messages = Message::factory()->count(10)->create(['conversation_id' => $conversation->id]);

        $this->actingAs($user)
            ->getJson("/api/chat/conversations/{$conversation->id}/messages")
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment(['has_liked' => false])
            ->assertJsonMissing(['has_liked' => true]) // no one liked
            ->assertSuccessful();

        $user->like($messages->first());

        $this->actingAs($user)
            ->getJson("/api/chat/conversations/{$conversation->id}/messages")
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment(['has_liked' => false])
            ->assertJsonFragment(['has_liked' => true])
            ->assertSuccessful();
    }
}
