<?php

namespace App\Modules\GiftCard;

use Illuminate\Support\Facades\Route;

class GiftCardRouterRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api', 'auth'],
            'prefix' => 'api',
        ], function () {
            Route::post('/gift-cards:activate', Endpoints\ActivateGiftCard::class);
        });
    }
}
