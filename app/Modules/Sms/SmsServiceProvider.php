<?php

namespace App\Modules\Sms;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sms', function () {
            return new EasySms(config('sms'));
        });

        $this->app->singleton(VerificationCode::class, function (Application $app) {
            /** @var CacheManager $cacheManager */
            $cacheManager = $app->make(CacheManager::class);

            /** @var LogManager $logManager */
            $logManager = $app->make(LogManager::class);

            return new VerificationCode(new EasySms(config('sms')), $cacheManager->store(), $logManager->channel());
        });
    }

    public function boot()
    {
        SmsRouteRegistrar::all();
    }
}
