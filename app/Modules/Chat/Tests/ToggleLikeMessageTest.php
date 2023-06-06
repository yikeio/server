<?php

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\User\User;
use Tests\TestCase;

class ToggleLikeMessageTest extends TestCase
{
    public function test_user_can_like_a_message()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        $message = Message::factory()->create(['creator_id' => $user->id, 'conversation_id' => $conversation->id]);

        $this->actingAs($user)
            ->postJson("/api/chat/messages/{$message->id}:toggle-like")
            ->assertSuccessful();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $message->id,
            'likeable_type' => $message->getMorphClass(),
        ]);

        $this->actingAs($user)
            ->postJson("/api/chat/messages/{$message->id}:toggle-like")
            ->assertSuccessful();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'likeable_id' => $message->id,
            'likeable_type' => $message->getMorphClass(),
        ]);
    }
}
