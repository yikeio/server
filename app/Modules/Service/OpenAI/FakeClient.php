<?php

namespace App\Modules\Service\OpenAI;

use App\Modules\Chat\Enums\MessageRole;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use OpenAI\Responses\Chat\CreateStreamedResponse;

class FakeClient
{
    public function __construct(protected Application $app)
    {
    }

    public function chat(): static
    {
        return $this;
    }

    public function createStreamed(): array
    {
        /** @var Generator $generator */
        $generator = $this->app->make(Generator::class);

        $text = $generator->paragraphs(random_int(1, 4), true);

        $words = str_split($text, 1);

        $response = [];

        foreach ($words as $word) {
            $response[] = CreateStreamedResponse::from([
                'id' => 'chatcmpl-71vWOjIJX0jfrGJDROYnUFcUiVJed',
                'object' => 'chat.completion.chunk',
                'created' => 1680693796,
                'model' => 'gpt-3.5-turbo',
                'choices' => [
                    [
                        'index' => 0,
                        'delta' => [
                            'role' => MessageRole::ASSISTANT->value,
                            'content' => $word,
                        ],
                        'finish_reason' => null,
                    ],
                ],
            ]);
        }

        $response[] = CreateStreamedResponse::from([
            'id' => 'chatcmpl-71vWOjIJX0jfrGJDROYnUFcUiVJed',
            'object' => 'chat.completion.chunk',
            'created' => 1680693796,
            'model' => 'gpt-3.5-turbo',
            'choices' => [
                [
                    'index' => 0,
                    'delta' => [],
                    'finish_reason' => 'stop',
                ],
            ],
        ]);

        return $response;
    }
}
