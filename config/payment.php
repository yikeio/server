<?php

return [
    'gateway' => env('PAYMENT_GATEWAY', 'payjs'),

    'reward' => [
        // 付款成功后的奖励比例, 单位: %, 最大 50%
        'rate' => [
            // 付款者
            'to_self' => env('PAYMENT_REWARD_RATE_TO_SELF', 3),
            // 推荐人
            'to_referrer' => env('PAYMENT_REWARD_RATE_TO_REFERRER', 10),
        ],
    ],

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

                'ttl' => 3600,
            ],
        ],
    ],
];
