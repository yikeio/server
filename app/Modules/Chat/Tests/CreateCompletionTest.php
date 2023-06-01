<?php

namespace App\Modules\Chat\Tests;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Jobs\SummarizeConversation;
use App\Modules\Chat\Message;
use App\Modules\Service\OpenAI\Tokenizer;
use App\Modules\User\User;
use Illuminate\Support\Facades\Bus;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateCompletionTest extends TestCase
{
    public function test_create_completion()
    {
        Bus::fake(SummarizeConversation::class);

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
        $conversation = Conversation::factory()->create(['creator_id' => $user->id, 'tokens_count' => 0, 'title' => '新的聊天']);

        Message::factory()->create(['conversation_id' => $conversation->id, 'role' => MessageRole::USER, 'tokens_count' => 0]);

        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions")
            ->streamedContent();

        Bus::assertDispatched(SummarizeConversation::class);

        $this->assertDatabaseCount('messages', 2);
        $this->assertDatabaseCount('quota_usages', 1);
        $this->assertDatabaseCount('quotas', 1);
        $this->assertEquals(1000, $user->getUsingQuota()->tokens_count);
        $this->assertEquals(8, $user->getUsingQuota()->used_tokens_count);
        $this->assertEquals(992, $user->getUsingQuota()->available_tokens_count);
    }
}
