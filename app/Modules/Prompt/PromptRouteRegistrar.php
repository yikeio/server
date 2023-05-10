<?php

namespace App\Modules\Prompt;

use App\Modules\Prompt\Endpoints\ListPrompts;
use Illuminate\Support\Facades\Route;

class PromptRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api/',
        ], function () {
            Route::get('prompts', ListPrompts::class)->name('prompts.index')->middleware('throttle:60,1');
        });
    }
}
