<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Chat\Conversation;

class ListConversations
{
    public function __invoke()
    {
        return Conversation::with('creator')->paginate(15);
    }
}
