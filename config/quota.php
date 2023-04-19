<?php

use App\Modules\Payment\Processors\GrantQuotaProcessor;

return [
    'pricings' => [
        'weekly' => [
            'title' => '7 天卡',
            'tokens_count' => 300 * 1000,
            'days' => 7,
            'price' => floatval(env('QUOTA_PRICINGS_WEEKLY_PRICE', 9.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 300 * 1000,
                        'days' => 7,
                    ],
                ],
            ],
        ],

        'biweekly' => [
            'title' => '15 天卡',
            'tokens_count' => 700 * 1000,
            'days' => 15,
            'price' => floatval(env('QUOTA_PRICINGS_BIWEEKLY_PRICE', 19.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 700 * 1000,
                        'days' => 15,
                    ],
                ],
            ],
        ],

        'monthly' => [
            'title' => '30 天卡',
            'tokens_count' => 1200 * 1000,
            'days' => 30,
            'price' => floatval(env('QUOTA_PRICINGS_MONTHLY_PRICE', 29.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 1200 * 1000,
                        'days' => 30,
                    ],
                ],
            ],
        ],
    ],

    'defaults' => [
        'chat' => [
            'tokens_count' => 1000,
            'days' => 1,
        ],
    ],
];
