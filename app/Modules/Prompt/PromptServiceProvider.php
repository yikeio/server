<?php

namespace App\Modules\Prompt;

use Illuminate\Support\ServiceProvider;

class PromptServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PromptRouteRegistrar::all();
    }
}
