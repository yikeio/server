<?php

namespace App\Modules\Auth\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class CreateClientTest extends TestCase
{
    public function test_create_client()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/oauth/clients', [
                'name' => 'Test Client',
                'redirect' => 'http://localhost',
            ])
            ->assertSuccessful();
    }
}
