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
            ->take(2)
            ->pluck('content');

        /** @var Client $client */
        $client = app(Client::class);

        $body = [
            ...config('openai.chat'),
            'messages' => [
                [
                    'role' => MessageRole::USER->value,
                    'content' => '请根据下列内容总结一个标题，如果无法提供，请返回 failed：'.$messages->implode("\n"),
                ],
            ],
        ];

        $response = $client->chat()->create($body);

        /** @var CreateResponseChoice $choice */
        $choice = Arr::first($response->choices);

        if (empty($choice)) {
            return;
        }

        if ($choice->message->content === 'failed') {
            return;
        }

        $this->conversation->title = $choice->message->content;
        $this->conversation->timestamps = false;
        $this->conversation->saveQuietly();
    }
}
