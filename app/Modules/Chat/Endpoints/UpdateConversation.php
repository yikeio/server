<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Requests\UpdateConversationRequest;
use App\Modules\Common\Endpoints\Endpoint;

class UpdateConversation extends Endpoint
{
    public function __invoke(UpdateConversationRequest $request, Conversation $conversation): Conversation
    {
        $this->authorize('update', $conversation);

        $conversation->update($request->only(['title']));

        return $conversation;
    }
}
