<?php


use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Message;
use App\Modules\User\User;
use Tests\TestCase;

class AbortCompletionTest extends TestCase
{
    public function test_abort_a_message()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Conversation $conversation */
        $conversation = Conversation::factory()->create(['creator_id' => $user->id]);

        // 不存在的消息
        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions:abort", [
                'abort_at_length' => 5,
            ])
            ->assertNotFound();


        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'creator_id' => $user->id,
            'role' => MessageRole::USER,
            'content' => '1234567890'
        ]);

        // 最后一条不是助手的消息
        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions:abort", [
                'abort_at_length' => 5,
            ])
            ->assertNotFound();

        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'creator_id' => $user->id,
            'role' => MessageRole::ASSISTANT,
            'content' => '1234567890'
        ]);
        // 正常
        $this->actingAs($user)
            ->postJson("/api/chat/conversations/{$conversation->id}/completions:abort", [
                'abort_at_length' => 5,
            ])
            ->assertSuccessful();

        $message->refresh();

        $this->assertSame('12345', $message->content);
    }
}
