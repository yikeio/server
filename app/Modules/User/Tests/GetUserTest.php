<?php

namespace App\Modules\User\Tests;

use App\Modules\User\User;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    public function test_get_user()
    {
        $referrer = User::factory()->create();
        $user = User::factory()->create([
            'referrer_id' => $referrer->id,
        ]);

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'phone_number',
                'paid_total',
                'referrer',
            ])
            ->assertSuccessful();
    }
}
