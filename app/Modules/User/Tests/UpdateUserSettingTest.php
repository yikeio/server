<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class UpdateUserSettingTest extends TestCase
{
    public function test_update_user_setting()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson('/api/settings/chat_contexts_count', [
                'value' => 10,
            ])
            ->assertSuccessful();
    }
}
