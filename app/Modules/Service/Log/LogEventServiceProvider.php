<?php

namespace App\Modules\Service\Log;

use App\Modules\Service\Log\Listeners\HttpClientListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Http\Client\Events\ConnectionFailed;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;

class LogEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ConnectionFailed::class => [
            [HttpClientListener::class, 'handleConnectionFailed'],
        ],
        RequestSending::class => [
            [HttpClientListener::class, 'handleRequestSending'],
        ],
        ResponseReceived::class => [
            [HttpClientListener::class, 'handleResponseReceived'],
        ],
    ];
}
