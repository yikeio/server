<?php

namespace App\Modules\Service\OpenAI;

use App\Modules\Chat\Enums\MessageRole;
use OpenAI\Responses\Chat\CreateStreamedResponse;

class FakeClient
{
    public function chat(): static
    {
        return $this;
    }

    public function createStreamed(): array
    {
        return [
            CreateStreamedResponse::from([
                'id' => 'chatcmpl-71vWOjIJX0jfrGJDROYnUFcUiVJed',
                'object' => 'chat.completion.chunk',
                'created' => 1680693796,
                'model' => 'gpt-3.5-turbo',
                'choices' => [
                    [
                        'index' => 0,
                        'delta' => [
                            'role' => MessageRole::ASSISTANT->value,
                            'content' => 'Hello, OpenAI!',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
            ]),
        ];
    }
}
