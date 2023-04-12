<?php

namespace App\Modules\Auth\Tests;

use App\Modules\User\User;
use Laravel\Passport\Client;
use Tests\TestCase;

class DeleteClientTest extends TestCase
{
    public function test_delete_client()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Client $client */
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->deleteJson('/oauth/clients/'.$client->id)
            ->assertSuccessful();
    }
}
