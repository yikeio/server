<?php

namespace App\Modules\Service\Socialite\Providers;

class Google extends \Overtrue\Socialite\Providers\Google
{
    protected string $endpoint;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->endpoint = $config['endpoint'] ?? 'https://www.googleapis.com';
    }

    protected function getTokenUrl(): string
    {
        return "{$this->endpoint}/oauth2/v4/token";
    }

    protected function getUserByToken(string $token, ?array $query = []): array
    {
        $response = $this->getHttpClient()->get("{$this->endpoint}/userinfo/v2/me", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return $this->fromJsonBody($response);
    }
}
