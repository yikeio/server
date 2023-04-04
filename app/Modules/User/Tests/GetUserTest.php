<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    public function test_get_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertSuccessful();
    }
}
