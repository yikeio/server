<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Actions\RefreshConversationActiveAt;
use App\Modules\Chat\Actions\RefreshConversationMessagesCount;
use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Requests\CreateMessageRequest;
use App\Modules\Common\Endpoints\Endpoint;

class CreateMessage extends Endpoint
{
    public function __invoke(CreateMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        $message = $conversation->messages()->create([
            'role' => MessageRole::USER->value,
            'content' => $request->input('content'),
        ]);

        RefreshConversationActiveAt::run($conversation);
        RefreshConversationMessagesCount::run($conversation);

        return $message;
    }
}
