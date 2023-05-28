<?php

namespace App\Modules\Tag;

use Illuminate\Support\ServiceProvider;

class TagServiceProvider extends ServiceProvider
{
    public function boot()
    {
        TagRouterRegistrar::all();
    }
}
