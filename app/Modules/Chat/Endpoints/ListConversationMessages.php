<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class ListConversationMessages extends Endpoint
{
    public function __invoke(Request $request, Conversation $conversation)
    {
        $this->authorize('get', $conversation);

        return $conversation->messages()->get();
    }
}
