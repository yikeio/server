<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Chat\Actions\InvokeTokenizer;
use App\Modules\Chat\Actions\RefreshConversationActiveAt;
use App\Modules\Chat\Actions\RefreshConversationMessagesCount;
use App\Modules\Chat\Actions\RefreshConversationTokensCount;
use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Requests\CreateSmartMessageRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Actions\ConsumeUserQuota;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Security\Actions\EncryptString;
use App\Modules\Service\Log\Actions\CreateErrorLog;
use App\Modules\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use OpenAI\Responses\Chat\CreateStreamedResponseChoice;
use Throwable;

class CreateSmartMessage extends Endpoint
{
    public function __invoke(CreateSmartMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        /** @var User $user */
        $user = $request->user();

        /** @var Client $client */
        $client = app(Client::class);

        $messages = $conversation->messages()
            ->get(['role', 'content'])
            ->toArray();

        try {
            $body = [
                ...config('openai.chat'),
                'user' => EncryptString::run($user->id),
                'messages' => $messages,
            ];

            Log::info('[CHAT] - 调用 OpenAI 入参', $body);

            $stream = $client->chat()->createStreamed($body);
        } catch (Throwable $e) {
            CreateErrorLog::run('[CHAT] - 调用 OpenAI 失败', [
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'messages' => $messages,
            ], $e);

            abort(500, '服务器开小差了，请稍后再试');
        }

        return response()->stream(function () use ($user, $stream, $conversation, $messages) {
            $contents = [];

            /** @var CreateStreamedResponse $response */
            foreach ($stream as $response) {
                /** @var CreateStreamedResponseChoice $choice */
                $choice = Arr::first($response->choices);

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

            $usage = $this->getUsage($messages, $content, config('openai.chat.model'));

            $conversation->messages()->create([
                'role' => MessageRole::ASSISTANT,
                'content' => $content,
                'raws' => array_merge($usage, $response->toArray()),
                'tokens_count' => $usage['tokens_count'],
            ]);

            ConsumeUserQuota::run($user, QuotaType::CHAT, $usage['tokens_count']);
            RefreshConversationActiveAt::run($conversation);
            RefreshConversationMessagesCount::run($conversation);
            RefreshConversationTokensCount::run($conversation);
        }, 200, [
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
        ]);
    }

    protected function getUsage(array $messages, string $completion, string $model): array
    {
        $prompts = [];

        foreach ($messages as $message) {
            $prompts[] = sprintf('%s:%s', $message['role'], $message['content']);
        }

        $prompt = implode("\n\n", $prompts);

        $promptTokens = count(InvokeTokenizer::run($prompt, $model)) + 8;
        $completionTokens = count(InvokeTokenizer::run($completion, $model));

        return [
            'prompt_tokens_count' => $promptTokens,
            'completion_tokens_count' => $completionTokens,
            'tokens_count' => $promptTokens + $completionTokens,
        ];
    }
}
