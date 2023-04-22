<?php

namespace App\Modules\Service\Socialite;

use App\Modules\Service\Socialite\Providers\GitHub;
use App\Modules\Service\Socialite\Providers\Google;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Overtrue\Socialite\SocialiteManager;

class SocialiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SocialiteManager::class, function (Application $app) {
            $manager = new SocialiteManager(config('socialite'));

            $manager->extend(\Overtrue\Socialite\Providers\GitHub::NAME, function (array $config) {
                return new GitHub($config);
            });

            $manager->extend(\Overtrue\Socialite\Providers\Google::NAME, function (array $config) {
                return new Google($config);
            });

            return $manager;
        });
    }
}
