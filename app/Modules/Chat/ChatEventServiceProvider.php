<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Events\CompletionCreated;
use App\Modules\Chat\Listeners\CompletionRecorder;
use App\Modules\Quota\Listeners\ConsumeUserQuota;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class ChatEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        CompletionCreated::class => [
            ConsumeUserQuota::class,
            CompletionRecorder::class,
        ],
    ];
}
