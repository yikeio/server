<?php

namespace App\Modules\Service\Snowflake;

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Support\ServiceProvider;

class SnowflakeServiceProvider extends ServiceProvider
{
    private const START_DATE = '2023-04-04';

    public function register(): void
    {
        $this->app->singleton('snowflake', function () {
            return (new Snowflake())
                ->setStartTimeStamp(strtotime(self::START_DATE) * 1000)
                ->setSequenceResolver(new LaravelSequenceResolver(
                    $this->app->get('cache')->store()
                ));
        });
    }

    public function boot(): void
    {
    }
}
