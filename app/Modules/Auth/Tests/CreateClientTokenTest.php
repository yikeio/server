<?php

namespace App\Modules\Auth\Tests;

use App\Modules\User\User;
use Laravel\Passport\Client;
use Tests\TestCase;

class CreateClientTokenTest extends TestCase
{
    public function test_create_client_token()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Client $client */
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '*',
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
            ]);
    }
}
