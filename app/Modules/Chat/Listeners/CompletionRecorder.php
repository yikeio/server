<?php

namespace App\Modules\Chat\Listeners;

use App\Modules\Chat\Actions\RefreshConversationActiveAt;
use App\Modules\Chat\Actions\RefreshConversationMessagesCount;
use App\Modules\Chat\Actions\RefreshConversationTokensCount;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Events\CompletionCreated;

class CompletionRecorder
{
    public function handle(CompletionCreated $event)
    {
        $completion = $event->getCompletion();
        $conversation = $completion->getConversation();

        $message = [
            'role' => MessageRole::ASSISTANT,
            'content' => $completion->getValue(),
            'raws' => [
                ...$completion->getRaws(),
                'usage' => $event->getUsage(),
            ],
            'tokens_count' => $event->getTokensCount(),
        ];

        if (! empty($conversation)) {
            $conversation->messages()->create($message);
            RefreshConversationActiveAt::run($conversation);
            RefreshConversationMessagesCount::run($conversation);
            RefreshConversationTokensCount::run($conversation);
        }
    }
}
