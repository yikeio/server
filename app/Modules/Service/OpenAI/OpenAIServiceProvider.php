<?php

namespace App\Modules\Service\OpenAI;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client;

class OpenAIServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Tokenizer::class, function () {
            if (!$this->app->isProduction()) {
                return \Mockery::mock(Tokenizer::class, function($mock){
                    $mock->shouldReceive('tokenize')->andReturn([]);
                })->makePartial();
            }

            return new Tokenizer(config('openai.tokenizer'));
        });

        $this->app->singleton(Client::class, function (Application $app) {
            if ($app->isProduction()) {
                $keys = explode(',', config('openai.api_key'));

                return OpenAI::factory()
                    ->withBaseUri(config('openai.endpoint'))
                    ->withApiKey(Arr::random($keys))
                    ->make();
            }

            return new FakeClient($app);
        });
    }
}
