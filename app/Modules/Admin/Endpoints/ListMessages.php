<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use Illuminate\Http\Request;

class ListMessages
{
    public function __invoke(Request $request)
    {
        return Message::with(['creator'])->filter($request->query())->latest('created_at')->paginate(15);
    }
}
