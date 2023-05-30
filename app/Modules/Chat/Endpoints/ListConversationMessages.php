<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Security\Actions\CheckSize;
use Illuminate\Http\Request;

class ListConversationMessages extends Endpoint
{
    public function __invoke(Request $request, Conversation $conversation)
    {
        $this->authorize('get', $conversation);

        return $conversation->messages()
            ->orderBy('id')
            ->filter($request->query())
            ->paginate(CheckSize::run($request->query('per_page', 15)));
    }
}
