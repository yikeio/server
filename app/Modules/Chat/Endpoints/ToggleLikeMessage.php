<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ToggleLikeMessage
{
    public function __invoke(Request $request, Message $message): Response
    {
        abort_if(! $message->creator->is($request->user()), 403);

        $request->user()->toggleLike($message);

        return response()->noContent();
    }
}
