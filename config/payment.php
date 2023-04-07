<?php

return [
    'gateway' => env('PAYMENT_GATEWAY', 'payjs'),

    'gateways' => [
        'payjs' => [
            'merchant_id' => env('PAYJS_MERCHANT_ID'),
            'secret_key' => env('PAYJS_SECRET_KEY'),
            'notify_url' => env('PAYJS_NOTIFY_URL'),
            'endpoint' => env('PAYJS_ENDPOINT', 'https://payjs.cn'),

            'native' => [
                'default' => [
                    'no_credit' => 0,
                ],
            ],
        ],
    ],
];
