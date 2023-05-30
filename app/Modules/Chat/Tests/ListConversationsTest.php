<?php

use App\Modules\Chat\Conversation;
use App\Modules\Prompt\Prompt;
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

    public function test_user_can_list_self_conversations_with_filter()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();

        $prompt= Prompt::factory()->create(['creator_id' => $user->id]);

        /** @var Conversation $conversation */
        $conversation1 = Conversation::factory()->create(['creator_id' => $user->id]);
        $conversation2 = Conversation::factory()->create(['creator_id' => $user->id, 'prompt_id' => $prompt->id]);
        $conversation3 = Conversation::factory()->create(['creator_id' => $anotherUser->id]);
        $conversation4 = Conversation::factory()->create(['creator_id' => $anotherUser->id, 'prompt_id' => $prompt->id]);

        $this->actingAs($user)
            ->getJson('/api/chat/conversations')
            ->assertSuccessful()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $conversation1->id])
            ->assertJsonFragment(['id' => $conversation2->id])
            ->assertJsonMissing(['id' => $conversation3->id])
            ->assertJsonMissing(['id' => $conversation4->id]);

        $this->actingAs($user)
            ->getJson('/api/chat/conversations?prompt='.$prompt->id)
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $conversation2->id])
            ->assertJsonMissing(['id' => $conversation1->id])
            ->assertJsonMissing(['id' => $conversation4->id])
            ->assertJsonMissing(['id' => $conversation3->id]);
    }
}
