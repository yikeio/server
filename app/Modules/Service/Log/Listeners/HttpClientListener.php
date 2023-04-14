<?php

namespace App\Modules\Service\Log\Listeners;

use Illuminate\Http\Client\Events\ConnectionFailed;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Log;

class HttpClientListener
{
    public function handleConnectionFailed(ConnectionFailed $event)
    {
        Log::channel('service')->error('[HTTP_CLIENT_LISTENER] - Connection Failed', [
            'url' => $event->request->url(),
            'method' => $event->request->method(),
            'headers' => $event->request->headers(),
            'body' => $event->request->data(),
        ]);
    }

    public function handleRequestSending(RequestSending $event)
    {
        Log::channel('service')->info('[HTTP_CLIENT_LISTENER] - Request Sending', [
            'url' => $event->request->url(),
            'method' => $event->request->method(),
            'headers' => $event->request->headers(),
            'body' => $event->request->data(),
        ]);
    }

    public function handleResponseReceived(ResponseReceived $event)
    {
        Log::channel('service')->info('[HTTP_CLIENT_LISTENER] - Response Received', [
            'status' => $event->response->status(),
            'headers' => $event->response->headers(),
            'body' => $event->response->json(),
        ]);
    }
}
