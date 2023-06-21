<?php

use App\Modules\Payment\Processors\GrantQuotaProcessor;

return [
    'defaults' => [
        // 赠送新用户的免费配额
        'chat' => [
            'tokens_count' => 10000,
            'days' => 1,
        ],
    ],

    'pricings' => [
        'start' => [
            'title' => '体验',
            'tokens_count' => 100 * 1000,
            'days' => 7,
            'is_popular' => false,
            'price' => floatval(env('QUOTA_PRICINGS_START_PRICE', 3.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 100 * 1000,
                        'days' => 7,
                    ],
                ],
            ],
        ],
        'weekly' => [
            'title' => '周套餐',
            'tokens_count' => 300 * 1000,
            'days' => 7,
            'is_popular' => false,
            'price' => floatval(env('QUOTA_PRICINGS_WEEKLY_PRICE', 5.9)),
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
            'title' => '双周套餐',
            'tokens_count' => 700 * 1000,
            'days' => 15,
            'is_popular' => false,
            'price' => floatval(env('QUOTA_PRICINGS_BIWEEKLY_PRICE', 14.9)),
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
            'title' => '月套餐',
            'tokens_count' => 1500 * 1000,
            'days' => 30,
            'is_popular' => true,
            'price' => floatval(env('QUOTA_PRICINGS_MONTHLY_PRICE', 26.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 1500 * 1000,
                        'days' => 30,
                    ],
                ],
            ],
        ],

        'half-year' => [
            'title' => '半年套餐',
            'tokens_count' => 5000 * 1000,
            'days' => 180,
            'is_popular' => false,
            'price' => floatval(env('QUOTA_PRICINGS_HALFYEAR_PRICE', 129.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 5000 * 1000,
                        'days' => 180,
                    ],
                ],
            ],
        ],

        'year' => [
            'title' => '年套餐',
            'tokens_count' => 12000 * 1000,
            'days' => 365,
            'is_popular' => false,
            'price' => floatval(env('QUOTA_PRICINGS_YEAR_PRICE', 229.9)),
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => 12000 * 1000,
                        'days' => 365,
                    ],
                ],
            ],
        ],
    ],
];
