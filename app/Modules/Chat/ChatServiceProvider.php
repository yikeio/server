<?php

namespace App\Modules\Chat;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::policy(Conversation::class, ConversationPolicy::class);

        ChatRouteRegistrar::all();
    }
}
