<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Message;
use App\Modules\Service\OpenAI\Tokenizer;
use App\Modules\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateCompletionTest extends TestCase
{
    public function test_create_completion()
    {
        $this->mock(Tokenizer::class, function (MockInterface $mock) {
            $mock->shouldReceive('setModel');
            $mock->shouldReceive('predict')->andReturn(10);
            $mock->shouldReceive('predictUsage')->andReturn([
                'tokens_count' => 8,
                'prompt_tokens_count' => 5,
                'completion_tokens_count' => 3,
            ]);
        });

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        Message::factory()->create(['conversation_id' => $conversation->id, 'role' => MessageRole::USER]);

        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions")
            ->streamedContent();

        $this->assertDatabaseCount('messages', 2);
        $this->assertDatabaseCount('quota_usages', 1);
        $this->assertDatabaseCount('quotas', 1);
        $this->assertEquals(1000, $user->getAvailableQuota()->tokens_count);
        $this->assertEquals(8, $user->getAvailableQuota()->used_tokens_count);
        $this->assertEquals(992, $user->getAvailableQuota()->available_tokens_count);
    }
}
