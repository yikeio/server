<?php

namespace App\Modules\Service\Socialite\Providers;

use Overtrue\Socialite\Contracts;

class GitHub extends \Overtrue\Socialite\Providers\GitHub
{
    protected string $endpoint;

    protected string $tokenEndpoint;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->endpoint = $config['endpoint'] ?? 'https://api.github.com';
        $this->tokenEndpoint = $config['token_endpoint'] ?? 'https://github.com';
    }

    protected function getTokenUrl(): string
    {
        return "{$this->tokenEndpoint}/login/oauth/access_token";
    }

    protected function getUserByToken(string $token): array
    {
        $userUrl = "{$this->endpoint}/user";

        $response = $this->getHttpClient()->get(
            $userUrl,
            $this->createAuthorizationHeaders($token)
        );

        $user = $this->fromJsonBody($response);

        if (\in_array('user:email', $this->scopes)) {
            $user[Contracts\ABNF_EMAIL] = $this->getEmailByToken($token);
        }

        return $user;
    }

    protected function getEmailByToken(string $token): string
    {
        $emailsUrl = "{$this->endpoint}/user/emails";

        try {
            $response = $this->getHttpClient()->get(
                $emailsUrl,
                $this->createAuthorizationHeaders($token)
            );
        } catch (\Throwable $e) {
            return '';
        }

        foreach ($this->fromJsonBody($response) as $email) {
            if ($email['primary'] && $email['verified']) {
                return $email[Contracts\ABNF_EMAIL];
            }
        }

        return '';
    }
}
