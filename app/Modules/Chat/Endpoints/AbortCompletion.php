<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Requests\CreateConversationRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbortCompletion extends Endpoint
{
    use ValidatesRequests;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request, Conversation $conversation): Conversation
    {
        $this->validate($request, [
            'abort_at_length' => 'required|integer|min:0',
        ]);

        abort_if($request->user()->id !== $conversation->creator_id, 403, '您没有权限');

        $message = $conversation->messages()->where('role', MessageRole::ASSISTANT)->first();

        if (!$message) {
            abort(404, '没有找到对应的消息');
        }

        $message->update([
            'content' => Str::substr($message->content, 0, $request->input('abort_at_length')),
        ]);

        return $conversation;
    }
}
