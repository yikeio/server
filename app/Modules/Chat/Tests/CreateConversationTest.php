<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Prompt\Prompt;
use App\Modules\User\User;
use Tests\TestCase;

class CreateConversationTest extends TestCase
{
    public function test_create_conversation_with_prompt_id()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $prompt = Prompt::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/chat/conversations', [
                'title' => 'title',
                'prompt_id' => $prompt->id,
            ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'prompt_id' => $prompt->id,
            ]);
    }

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
