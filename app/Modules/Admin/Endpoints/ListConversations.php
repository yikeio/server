<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Chat\Conversation;
use Illuminate\Http\Request;

class ListConversations
{
    public function __invoke(Request $request)
    {
        return Conversation::with(['creator', 'prompt'])->latest('created_at')->filter($request->query())->paginate(15);
    }
}
