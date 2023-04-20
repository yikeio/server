<?php

namespace App\Modules\Service\State;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class StateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StateManager::class, function (Application $app) {
            /** @var CacheManager $cacheManager */
            $cacheManager = $app->make(CacheManager::class);

            return new StateManager($cacheManager->store());
        });
    }
}
