<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TruncateConversation extends Endpoint
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Conversation $conversation): Response
    {
        $this->authorize('update', $conversation);

        $conversation->messages->each->delete();

        return response()->noContent();
    }
}
