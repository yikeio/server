<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class CreateMessageTest extends TestCase
{
    public function test_create_message()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/messages", [
                'content' => 'content',
            ])
            ->assertSuccessful();
    }
}
