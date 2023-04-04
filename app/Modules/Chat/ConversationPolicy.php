<?php

namespace App\Modules\Chat;

use App\Modules\User\User;

class ConversationPolicy
{
    public function get(User $user, Conversation $conversation): bool
    {
        return $user->is($conversation->creator);
    }

    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->is($conversation->creator);
    }

    public function update(User $user, Conversation $conversation): bool
    {
        return $user->is($conversation->creator);
    }
}
