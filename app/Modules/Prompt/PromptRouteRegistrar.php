<?php

namespace App\Modules\Prompt;

use App\Modules\Prompt\Endpoints\CreatePrompt;
use App\Modules\Prompt\Endpoints\DeletePrompt;
use App\Modules\Prompt\Endpoints\GetPrompt;
use App\Modules\Prompt\Endpoints\ListFeaturedPrompts;
use App\Modules\Prompt\Endpoints\ListPrompts;
use App\Modules\Prompt\Endpoints\ListUserPrompts;
use App\Modules\Prompt\Endpoints\UpdatePrompt;
use Illuminate\Support\Facades\Route;

class PromptRouteRegistrar
{
    public static function all(): void
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api/',
        ], function () {
            // 全部
            Route::get('prompts', ListPrompts::class)->name('prompts.index')->middleware('throttle:60,1');
            // 精选
            Route::get('prompts:featured', ListFeaturedPrompts::class)->name('prompts.featured.index')->middleware('throttle:60,1');

            Route::get('prompts/{prompt}', GetPrompt::class)->name('prompts.show')->middleware('throttle:60,1');

            Route::group(['middleware' => ['auth']], function () {
                // 我的
                Route::get('prompts:mine', ListUserPrompts::class)->name('prompts.users.index')->middleware('throttle:60,1');

                Route::post('prompts', CreatePrompt::class)->name('prompts.store')->middleware('throttle:5,1');
                Route::patch('prompts/{prompt}', UpdatePrompt::class)->name('prompts.update')->middleware('throttle:60,1');
                Route::delete('prompts/{prompt}', DeletePrompt::class)->name('prompts.destroy')->middleware('throttle:10,1');
            });
        });
    }
}
