<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Requests\CreateMessageRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;

class CreateMessage extends Endpoint
{
    public function __invoke(CreateMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        /** @var User $user */
        $user = $request->user();

        return $conversation->messages()->create([
            'creator_id' => $user->id,
            'role' => MessageRole::USER->value,
            'content' => $request->input('content'),
            'tokens_count' => 0,
        ]);
    }
}
