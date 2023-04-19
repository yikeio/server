<?php

namespace App\Modules\Service\OpenAI;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Tokenizer::class, function () {
            return new Tokenizer(config('openai.tokenizer'));
        });

        $this->app->singleton(Client::class, function (Application $app) {
            if ($app->isProduction()) {
                return OpenAI::factory()
                    ->withBaseUri(config('openai.endpoint'))
                    ->withApiKey(config('openai.api_key'))
                    ->make();
            }

            return new FakeClient($app);
        });
    }
}
