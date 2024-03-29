<?php

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class GetConversationTest extends TestCase
{
    public function test_user_can_get_conversation()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation1 = Conversation::factory()->create(['creator_id' => $user->id]);
        $conversation2 = Conversation::factory()->create(['creator_id' => $user->id]);
        $conversation3 = Conversation::factory()->create(['creator_id' => $anotherUser->id]);

        $this->actingAs($user)->getJson("/api/chat/conversations/{$conversation1->id}")->assertSuccessful();
        $this->actingAs($user)->getJson("/api/chat/conversations/{$conversation2->id}")->assertSuccessful();
        $this->actingAs($user)->getJson("/api/chat/conversations/{$conversation3->id}")->assertForbidden();
    }
}
