<?php

namespace App\Modules\Chat\Listeners;

use App\Modules\Chat\Actions\RefreshConversationActiveAt;
use App\Modules\Chat\Actions\RefreshConversationMessagesCount;
use App\Modules\Chat\Actions\RefreshConversationTokensCount;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Events\CompletionCreated;
use App\Modules\Chat\Message;
use App\Modules\Service\OpenAI\Tokenizer;

class CompletionRecorder
{
    public function handle(CompletionCreated $event)
    {
        $completion = $event->getCompletion();

        $conversation = $completion->getConversation();

        /** @var Tokenizer $tokenizer */
        $tokenizer = app(Tokenizer::class);
        $tokenizer->setModel($completion->getModel());

        $usage = $tokenizer->predictUsage($completion->getPrompts(), $completion->getValue());

        Message::query()->create([
            'creator_id' => $completion->getCreator()->id,
            'role' => MessageRole::ASSISTANT,
            'content' => $completion->getValue(),
            'raws' => [
                ...$completion->getRaws(),
                'usage' => $usage,
            ],
            'tokens_count' => $usage['tokens_count'] ?? 0,
            'conversation_id' => $conversation->id,
            'quota_id' => $completion->getQuota()->id,
        ]);

        if (! empty($conversation)) {
            RefreshConversationActiveAt::run($conversation);
            RefreshConversationMessagesCount::run($conversation);
            RefreshConversationTokensCount::run($conversation);
        }
    }
}
