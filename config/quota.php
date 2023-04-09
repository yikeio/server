<?php

use App\Modules\Payment\Processors\GrantQuotaProcessor;

return [
    'pricings' => [
        'chat' => [
            'weekly' => [
                'title' => '7 天卡',
                'tokens_count' => 300 * 1000,
                'days' => 7,
                'price' => 0.1,
                'processors' => [
                    [
                        'class' => GrantQuotaProcessor::class,
                        'parameters' => [
                            'quota_type' => 'chat',
                            'quota_meter' => 'token',
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
                'price' => 0.1,
                'processors' => [
                    [
                        'class' => GrantQuotaProcessor::class,
                        'parameters' => [
                            'quota_type' => 'chat',
                            'quota_meter' => 'token',
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
                'price' => 0.1,
                'processors' => [
                    [
                        'class' => GrantQuotaProcessor::class,
                        'parameters' => [
                            'quota_type' => 'chat',
                            'quota_meter' => 'token',
                            'tokens_count' => 1200 * 1000,
                            'days' => 30,
                        ],
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
