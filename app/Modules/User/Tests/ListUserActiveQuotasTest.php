<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class ListUserActiveQuotasTest extends TestCase
{
    public function test_list_user_active_quotas()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/users/{$user->id}/active-quotas")
            ->assertSuccessful();
    }
}
