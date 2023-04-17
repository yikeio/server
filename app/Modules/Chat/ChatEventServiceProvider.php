<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Events\CompletionCreated;
use App\Modules\Chat\Listeners\CompletionRecorder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class ChatEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        CompletionCreated::class => [
            CompletionRecorder::class,
        ],
    ];
}
