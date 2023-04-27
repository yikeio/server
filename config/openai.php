<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'endpoint' => env('OPENAI_ENDPOINT'),

    // https://platform.openai.com/docs/models/overview
    // https://platform.openai.com/docs/api-reference/chat
    'chat' => [
        'model' => 'gpt-3.5-turbo',
        'presence_penalty' => 1,
        'temperature' => 0.8,
        //        'max_tokens' => 4096,
    ],

    'tokenizer' => [
        'endpoint' => env('OPENAI_TOKENIZER_ENDPOINT'),
    ],
];
