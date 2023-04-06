<?php

return [
    'api_key' => env('OPENAI_API_KEY'),

    'chat' => [
        'model' => 'gpt-3.5-turbo',
        'presence_penalty' => 1,
        'temperature' => 0.8,
        'max_tokens' => 1000,
    ],

    'tokenizer' => [
        'endpoint' => env('OPENAI_TOKENIZER_ENDPOINT'),
    ],
];
