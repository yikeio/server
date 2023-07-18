<?php

namespace App\Modules\Chat\Actions;

use App\Modules\Chat\Conversation;
use App\Modules\Common\Actions\Action;

class RefreshConversation extends Action
{
    public function handle(Conversation|int|null $conversation)
    {
        if (is_int($conversation)) {
            $conversation = Conversation::query()->find($conversation);
        }

        if (empty($conversation)) {
            return;
        }

        if (empty($conversation->first_active_at)) {
            $conversation->first_active_at = now();
        }

        $conversation->last_active_at = now();
        $conversation->messages_count = $conversation->messages()->count();
        $conversation->tokens_count = $conversation->messages()->sum('tokens_count');

        $conversation->timestamps = false;
        $conversation->save();
    }
}
