<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Requests\CreateConversationRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;

class CreateConversation extends Endpoint
{
    public function __invoke(CreateConversationRequest $request): Conversation
    {
        /** @var User $user */
        $user = $request->user();

        $conversation = new Conversation();
        $conversation->title = $request->input('title');
        $conversation->prompt_id = $request->input('prompt_id') ?: 0;
        $user->conversations()->save($conversation);

        if ($conversation->prompt_id) {
            $conversation->messages()->create([
                'creator_id' => $user->id,
                'role' => MessageRole::ASSISTANT,
                'content' => $conversation->prompt->greeting,
                'tokens_count' => 0,
            ]);
        }

        return $conversation;
    }
}
