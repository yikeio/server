<?php

namespace App\Modules\Tag;

use App\Modules\Tag\Endpoints\ListTags;
use Illuminate\Support\Facades\Route;

class TagRouterRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api/',
        ], function () {
            Route::get('tags', ListTags::class)->name('tags.index')->middleware('throttle:600,1');
        });
    }
}
