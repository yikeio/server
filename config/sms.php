<?php

use Overtrue\EasySms\Strategies\OrderStrategy;

return [
    'regions' => [
        'CN' => '86',
    ],

    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            //            'qcloud',
            'errorlog',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => storage_path('logs/sms.log'),
        ],

        'qcloud' => [
            'sdk_app_id' => env('SMS_QCLOUD_SDK_APP_ID'),
            'secret_id' => env('SMS_QCLOUD_SECRET_ID'),
            'secret_key' => env('SMS_QCLOUD_SECRET_KEY'),
            'sign_name' => env('SMS_QCLOUD_SIGN_NAME'),
        ],
    ],
];
