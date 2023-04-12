<?php

namespace App\Modules\Chat\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class CreateConversationTest extends TestCase
{
    public function test_create_conversation()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/chat/conversations', [
                'title' => 'title',
            ])
            ->assertSuccessful();
    }
}
