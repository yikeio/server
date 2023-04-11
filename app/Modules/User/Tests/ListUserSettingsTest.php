<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class ListUserSettingsTest extends TestCase
{
    public function test_list_user_settings()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/users/{$user->id}/settings")
            ->assertSuccessful();
    }
}
