<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
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
        $user->conversations()->save($conversation);

        return $conversation;
    }
}
