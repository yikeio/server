<?php

namespace App\Modules\Auth\Tests;

use App\Modules\User\User;
use Laravel\Passport\Client;
use Tests\TestCase;

class ListUserClientsTest extends TestCase
{
    public function test_list_user_clients()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Client $client */
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->getJson('/oauth/clients')
            ->assertSuccessful()
            ->assertJsonCount(1);
    }
}
