<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class ListUserQuotasTest extends TestCase
{
    public function test_list_user_quotas()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/users/{$user->id}/quotas")
            ->assertJsonCount(1)
            ->assertSuccessful();
    }
}
