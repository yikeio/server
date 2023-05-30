<?php

namespace App\Modules\Service\OpenAI;

use App\Modules\Chat\Enums\MessageRole;
use Faker\Generator;
use Http\Discovery\Psr17FactoryDiscovery;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use OpenAI\Responses\StreamResponse;

class FakeClient
{
    public function __construct(protected Application $app)
    {
    }

    public function chat(): static
    {
        return $this;
    }

    public function createStreamed(): StreamResponse
    {
        /** @var Generator $generator */
        $generator = $this->app->make(Generator::class);

        $text = $generator->paragraphs(random_int(1, 10), true);

        $words = array_filter(explode(' ', $text));

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
                            'content' => "$word ",
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

        $file = tempnam(sys_get_temp_dir(), uniqid('chat-response-', true) . '.json');
        file_put_contents($file, json_encode($response));
        $resource = fopen($file, 'r+');
        $stream = Psr17FactoryDiscovery::findStreamFactory()
            ->createStreamFromResource($resource);
        $response = Psr17FactoryDiscovery::findResponseFactory()
            ->createResponse()
            ->withBody($stream);

        return new StreamResponse(CreateStreamedResponse::class, $response);
    }
}
