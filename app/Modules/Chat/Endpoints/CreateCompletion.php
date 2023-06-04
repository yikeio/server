<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Jobs\SummarizeConversation;
use App\Modules\Chat\Message;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Security\Actions\EncryptString;
use App\Modules\Service\OpenAI\FakeClient;
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

        $quota = $user->getUsingQuota();

        if (empty($quota)) {
            abort(403, '您没有可用的配额');
        }

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

        // 消息体头部插入提示词
        if ($conversation->prompt) {
            $prompt = $conversation->prompt->prompt_en ?: $conversation->prompt->prompt_cn;

            if ($prompt) {
                array_unshift($messages, [
                    'role' => MessageRole::SYSTEM->value,
                    'content' => $prompt,
                ]);
            }
        }

        if (! empty($messages)) {
            if ($tokenizer->predict($messages) >= config('openai.chat.max_tokens', 4096)) {
                abort(422, '附带历史消息长度超过限制，请降低附带历史消息数量或者新建聊天窗口');
            }
        }

        try {
            $body = [
                ...config('openai.chat'),
                'user' => EncryptString::run($user->id),
                'messages' => $messages,
            ];

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

        $completion = new Message();
        $completion->creator_id = $user->id;
        $completion->role = MessageRole::ASSISTANT;
        $completion->conversation_id = $conversation->id;
        $completion->quota_id = $quota->id;
        $completion->save();

        return response()->stream(function () use ($client, $stream, $messages, $completion, $tokenizer, $conversation) {
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

                $completion->content = implode('', array_filter($contents));
                $usage = $tokenizer->predictUsage($messages, $completion->content);
                $completion->tokens_count = $usage['tokens_count'] ?? 0;
                $completion->save();

                // 流式返回数据
                echo $choice->delta->content;

                if ($client instanceof FakeClient) {
                    usleep(random_int(1000, 9000));
                }

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }

            if (empty($response)) {
                $raws = [];
            } else {
                $raws = $response->toArray();
            }

            $usage = $tokenizer->predictUsage($messages, $completion->content);
            $completion->raw = [
                ...$raws,
                'choices' => $choices,
                'usage' => $usage,
            ];
            $completion->save();

            if ($conversation->title === '新的聊天') {
                SummarizeConversation::dispatch($conversation);
            }
        }, 200, [
            'X-Message-Id' => $completion->id,
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
