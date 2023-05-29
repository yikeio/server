<?php

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class ListConversationsTest extends TestCase
{
    public function test_user_can_list_self_conversations()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation1 = Conversation::factory()->create(['creator_id' => $user->id]);
        $conversation2 = Conversation::factory()->create(['creator_id' => $user->id]);
        $conversation3 = Conversation::factory()->create(['creator_id' => $anotherUser->id]);

        $this->actingAs($user)
            ->getJson('/api/chat/conversations')
            ->assertSuccessful()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $conversation1->id])
            ->assertJsonFragment(['id' => $conversation2->id])
            ->assertJsonMissing(['id' => $conversation3->id]);
    }
}
