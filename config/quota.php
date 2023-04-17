<?php

use App\Modules\Payment\Processors\GrantQuotaProcessor;

return [
    'pricings' => [
        'weekly' => [
            'title' => '7 天卡',
            'tokens_count' => 300 * 1000,
            'days' => 7,
            'price' => 9.9,
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
            'price' => 19.9,
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
            'price' => 29.9,
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
