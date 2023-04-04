<?php

namespace App\Modules\Chat\Actions;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Actions\Action;

class RefreshConversationActiveAt extends Action
{
    public function handle(Conversation $conversation): Conversation
    {
        if (empty($conversation->first_active_at)) {
            $conversation->first_active_at = now();
        }

        $conversation->last_active_at = now();

        $conversation->timestamps = false;
        $conversation->save();

        return $conversation;
    }
}
