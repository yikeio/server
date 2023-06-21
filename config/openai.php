<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'endpoint' => env('OPENAI_ENDPOINT'),
    'api_version' => env('OPENAI_API_VERSION'),

    // https://platform.openai.com/docs/models/overview
    // https://platform.openai.com/docs/api-reference/chat
    'chat' => [
        'model' => 'gpt-35-turbo',
        'presence_penalty' => 1,
        'temperature' => 0.8,
        //        'max_tokens' => 4096,
    ],

    'tokenizer' => [
        'endpoint' => env('OPENAI_TOKENIZER_ENDPOINT'),
    ],
];
