<?php

namespace App\Modules\GiftCard;

use Illuminate\Support\ServiceProvider;

class GiftCardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        GiftCardRouterRegistrar::all();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeGiftCard::class,
            ]);
        }
    }
}
