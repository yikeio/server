<?php

namespace App\Modules\User\Tests;

use App\Modules\Payment\Payment;
use App\Modules\User\User;
use Tests\TestCase;

class ListUserPaymentsTest extends TestCase
{
    public function test_list_user_payments()
    {
        /** @var User $user */
        $user = User::factory()->create();

        Payment::factory()->create([
            'creator_id' => $user->id,
            'gateway' => 'payjs',
            'gateway_number' => '1234567890',
        ]);

        $this->actingAs($user)
            ->getJson("/api/users/$user->id/payments")
            ->assertSuccessful()
            ->assertJsonCount(1);
    }
}
