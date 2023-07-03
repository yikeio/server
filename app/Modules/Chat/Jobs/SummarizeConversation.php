<?php

namespace App\Modules\Chat\Jobs;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenAI\Client;
use OpenAI\Responses\Chat\CreateResponseChoice;

class SummarizeConversation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Conversation $conversation)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $messages = $this->conversation
            ->messages()
            ->where('role', MessageRole::USER)
            ->take(2)
            ->pluck('content');

        /** @var Client $client */
        $client = app(Client::class);

        $messages->prepend("请根据内容总结一个标题（30 字以内），如果无法提供，请返回 6 个字符：'failed'。内容如下：");
        $body = [
            ...config('openai.chat'),
            'messages' => [
                [
                    'role' => MessageRole::USER->value,
                    'content' => $messages->implode("\n"),
                ],
            ],
        ];

        $response = $client->chat()->create($body);

        /** @var CreateResponseChoice $choice */
        $choice = Arr::first($response->choices);

        if (empty($choice)) {
            return;
        }

        if (str_contains($choice->message->content, 'failed')) {
            return;
        }

        $this->conversation->title = Str::limit($choice->message->content, 30);
        $this->conversation->timestamps = false;
        $this->conversation->saveQuietly();
    }
}
