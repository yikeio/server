<?php

namespace App\Modules\Service\Snowflake;

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SnowflakeServiceProvider extends ServiceProvider
{
    private const START_DATE = '2023-04-04';

    public function register(): void
    {
        $this->app->singleton(Snowflake::class, function (Application $app) {
            return (new Snowflake())
                ->setStartTimeStamp(strtotime(self::START_DATE) * 1000)
                ->setSequenceResolver(new LaravelSequenceResolver(Cache::store()));
        });
    }

    public function boot(): void
    {
    }
}
