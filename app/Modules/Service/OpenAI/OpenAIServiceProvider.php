<?php

namespace App\Modules\Service\OpenAI;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OpenAI\Client::class, function () {
            return OpenAI::factory()
                ->withBaseUri(config('openai.endpoint'))
                ->withApiKey(config('openai.api_key'))
                ->make();
        });
    }
}
