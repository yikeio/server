<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class DeleteConversation extends Endpoint
{
    public function __invoke(Request $request, Conversation $conversation): Conversation
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        return $conversation;
    }
}
