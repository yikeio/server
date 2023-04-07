<?php

namespace App\Modules\Auth;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class ClientManager
{
    protected ?Client $client;

    public function __construct(protected Application $app)
    {
    }

    public function getClient(): ?Client
    {
        if (empty($this->client)) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    public function getClientId(): ?string
    {
        return $this->getClient()?->id;
    }

    protected function resolveClient(): ?Client
    {
        $psr = (new PsrHttpFactory(
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory
        ))->createRequest($this->app->make(Request::class));

        try {
            $psr = $this->app->make(ResourceServer::class)->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            return null;
        }

        return $this->app->make(ClientRepository::class)->findActive($psr->getAttribute('oauth_client_id'));
    }
}
