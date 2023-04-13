<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Actions\InvokeTokenizer;
use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Message;
use App\Modules\Service\OpenAI\FakeClient;
use App\Modules\User\User;
use OpenAI\Client;
use Tests\TestCase;

class CreateCompletionTest extends TestCase
{
    public function test_create_completion()
    {
        InvokeTokenizer::shouldReceive('handle')
            ->andReturn([]);

        app()->bind(Client::class, function () {
            return new FakeClient();
        });

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        Message::factory()->create(['conversation_id' => $conversation->id, 'role' => MessageRole::USER]);

        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions")
            ->assertSuccessful();
    }
}
