<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Jobs\SummarizeConversation;
use App\Modules\Chat\Message;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Actions\CreateQuotaUsage;
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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CreateCompletion extends Endpoint
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, Conversation $conversation): StreamedResponse
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

        $contextMessages = $conversation->messages()
            ->offset($messagesCount - $contextsCount)
            ->take($contextsCount)
            ->get(['role', 'content'])
            ->toArray();

        // 消息体头部插入提示词
        if ($conversation->prompt) {
            $prompt = $conversation->prompt->prompt_en ?: $conversation->prompt->prompt_cn;

            if ($prompt) {
                array_unshift($contextMessages, [
                    'role' => MessageRole::SYSTEM->value,
                    'content' => $prompt,
                ]);
            }
        }

        if (! empty($contextMessages)) {
            if ($tokenizer->predict($contextMessages) >= config('openai.chat.max_tokens', 4096)) {
                abort(422, '附带历史消息长度超过限制，请降低附带历史消息数量或者新建聊天窗口');
            }
        }

        try {
            $body = [
                ...config('openai.chat'),
                'user' => EncryptString::run($user->id),
                'messages' => $contextMessages,
            ];

            Log::channel('service')->info('[CHAT] - 调用 OpenAI 入参', $body);

            $stream = $client->chat()->createStreamed($body);
        } catch (Throwable $e) {
            Log::error('[CHAT] - 调用 OpenAI 失败', [
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'messages' => $contextMessages,
                'exception' => $e,
            ]);

            abort(500, '服务器开小差了，请稍后再试');
        }

        $message = new Message();
        $message->creator_id = $user->id;
        $message->role = MessageRole::ASSISTANT;
        $message->conversation_id = $conversation->id;
        $message->quota_id = $quota->id;
        $message->save();

        $contents = collect();
        $choices = collect();

        $saveMessage = function () use ($message, $choices, $contents, $contextMessages, $tokenizer) {
            $message->content = $contents->filter()->implode('');
            $usage = $tokenizer->predictUsage($contextMessages, $message->content);
            $message->tokens_count = $usage['tokens_count'] ?? 0;
            $message->raw = [
                'choices' => $choices,
                'usage' => $usage,
            ];
            $message->save();
        };

        return response()->stream(function () use ($message, $saveMessage, $contents, $choices, $client, $stream, $conversation) {
            ignore_user_abort(true);

            /** @var CreateStreamedResponse $response */
            foreach ($stream as $response) {
                /** @var CreateStreamedResponseChoice $choice */
                $choice = Arr::first($response->choices);

                if (empty($choice)) {
                    continue;
                }

                if (connection_aborted()) {
                    return $saveMessage();
                }

                $choices->push($choice->toArray());

                if (empty($choice->delta->content)) {
                    continue;
                }

                $contents->push($choice->delta->content);

                echo $choice->delta->content;

                if ($client instanceof FakeClient) {
                    usleep(random_int(1000, 9000));
                }

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }

            $saveMessage();

            if ($conversation->messages()->where('role', MessageRole::USER)->count() == 1) {
                SummarizeConversation::dispatch($conversation);
            }

            CreateQuotaUsage::run($message);
        }, 200, [
            'X-Message-Id' => $message->id,
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
