<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\User\User;
use Tests\TestCase;

class UpdateConversationTest extends TestCase
{
    public function test_update_conversation()
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        $this->actingAs($user)
            ->putJson("/api/conversations/{$conversation->id}", [
                'title' => 'New title',
            ])
            ->assertSuccessful();
    }
}
