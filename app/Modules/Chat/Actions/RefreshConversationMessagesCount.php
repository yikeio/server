<?php

namespace App\Modules\Chat\Actions;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Actions\Action;

class RefreshConversationMessagesCount extends Action
{
    public function handle(Conversation $conversation): Conversation
    {
        $conversation->messages_count = $conversation->messages()->count();
        $conversation->timestamps = false;
        $conversation->save();

        return $conversation;
    }
}
