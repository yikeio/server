<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Completion;
use App\Modules\Chat\Conversation;
use App\Modules\Chat\Events\CompletionCreated;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Security\Actions\EncryptString;
use App\Modules\Service\OpenAI\Tokenizer;
use App\Modules\User\Enums\SettingKey;
use App\Modules\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use OpenAI\Responses\Chat\CreateStreamedResponseChoice;
use Throwable;

class CreateCompletion extends Endpoint
{
    public function __invoke(Request $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        /** @var User $user */
        $user = $request->user();

        /** @var Client $client */
        $client = app(Client::class);

        /** @var Tokenizer $tokenizer */
        $tokenizer = app(Tokenizer::class);
        $tokenizer->setModel(config('openai.chat.model'));

        $contextsCount = $user->getSetting(SettingKey::CHAT_CONTEXTS_COUNT);
        $messagesCount = $conversation->messages()->count();

        $messages = $conversation->messages()
            ->offset($messagesCount - $contextsCount)
            ->take($contextsCount)
            ->get(['role', 'content'])
            ->toArray();

        if (! empty($messages)) {
            if ($tokenizer->predict($messages) >= config('openai.chat.max_tokens')) {
                abort(422, '附带历史消息长度超过限制，请降低附带历史消息数量或者新建聊天窗口');
            }
        }

        try {
            $body = [
                ...config('openai.chat'),
                'user' => EncryptString::run($user->id),
                'messages' => $messages,
            ];

            // 这里把 max_tokens 移除，让 OpenAI 自适应
            unset($body['max_tokens']);

            Log::channel('service')->info('[CHAT] - 调用 OpenAI 入参', $body);

            $stream = $client->chat()->createStreamed($body);
        } catch (Throwable $e) {
            Log::error('[CHAT] - 调用 OpenAI 失败', [
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'messages' => $messages,
                'exception' => $e,
            ]);

            abort(500, '服务器开小差了，请稍后再试');
        }

        return response()->stream(function () use ($user, $stream, $conversation, $messages) {
            $contents = [];

            $choices = [];

            /** @var CreateStreamedResponse $response */
            foreach ($stream as $response) {
                /** @var CreateStreamedResponseChoice $choice */
                $choice = Arr::first($response->choices);

                if (empty($choice)) {
                    continue;
                }

                $choices[] = $choice->toArray();

                if (empty($choice->delta->content)) {
                    continue;
                }

                $contents[] = $choice->delta->content;

                // 流式返回数据
                echo $choice->delta->content;
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }

            $content = implode('', array_filter($contents));

            if (empty($response)) {
                $raws = [];
            } else {
                $raws = $response->toArray();
            }

            $completion = new Completion();
            $completion->setCreator($user);
            $completion->setConversation($conversation);
            $completion->setPrompts($messages);
            $completion->setValue($content);
            $completion->setRaws([
                ...$raws,
                'choices' => $choices,
            ]);

            event(new CompletionCreated($completion));
        }, 200, [
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
