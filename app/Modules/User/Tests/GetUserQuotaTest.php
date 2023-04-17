<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class GetUserQuotaTest extends TestCase
{
    public function test_get_user_quota()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/users/{$user->id}/quota")
            ->assertSuccessful();
    }
}
