<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use Illuminate\Http\Request;

class GetConversation
{
    public function __invoke(Request $request, Conversation $conversation): Conversation
    {
        abort_if($conversation->creator_id !== $request->user()->id, 403);

        $conversation->loadMissing('prompt');

        return $conversation;
    }
}
