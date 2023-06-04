<?php

namespace App\Modules\Quota\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class ListQuotasTest extends TestCase
{
    public function test_list_user_quotas()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/quotas')
            ->assertJsonCount(1)
            ->assertSuccessful();
    }
}
