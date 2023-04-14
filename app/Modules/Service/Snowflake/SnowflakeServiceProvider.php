<?php

namespace App\Modules\Service\Snowflake;

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SnowflakeServiceProvider extends ServiceProvider
{
    private const START_DATE = '2023-04-04';

    public function register(): void
    {
        $this->app->singleton(Snowflake::class, function (Application $app) {
            /** @var CacheManager $cacheManager */
            $cacheManager = $app->make(CacheManager::class);

            return (new Snowflake())
                ->setStartTimeStamp(strtotime(self::START_DATE) * 1000)
                ->setSequenceResolver(new LaravelSequenceResolver($cacheManager->store()));
        });
    }

    public function boot(): void
    {
    }
}
