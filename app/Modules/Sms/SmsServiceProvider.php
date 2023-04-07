<?php

namespace App\Modules\Sms;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sms', function () {
            return new EasySms(config('sms'));
        });

        $this->app->singleton(VerificationCode::class, function () {
            return new VerificationCode(new EasySms(config('sms')), Cache::store());
        });
    }

    public function boot()
    {
        SmsRouteRegistrar::all();
    }
}
