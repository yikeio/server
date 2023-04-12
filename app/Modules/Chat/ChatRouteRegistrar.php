<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Endpoints\CreateCompletion;
use App\Modules\Chat\Endpoints\CreateConversation;
use App\Modules\Chat\Endpoints\CreateMessage;
use App\Modules\Chat\Endpoints\DeleteConversation;
use App\Modules\Chat\Endpoints\ListConversationMessages;
use App\Modules\Chat\Endpoints\UpdateConversation;
use Illuminate\Support\Facades\Route;

class ChatRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api', 'auth'],
            'prefix' => 'api/chat',
        ], function () {
            Route::get('/conversations/{conversation}/messages', ListConversationMessages::class);
            Route::delete('/conversations/{conversation}', DeleteConversation::class);
            Route::put('/conversations/{conversation}', UpdateConversation::class)->middleware('throttle:10,1');

            Route::group([
                'middleware' => ['quota.check:chat'],
            ], function () {
                Route::post('/conversations/{conversation}/completions', CreateCompletion::class)->middleware('throttle:30,1');
                Route::post('/conversations/{conversation}/messages', CreateMessage::class)->middleware('throttle:30,1');
                Route::post('/conversations', CreateConversation::class)->middleware('throttle:30,1');
            });
        });
    }
}
