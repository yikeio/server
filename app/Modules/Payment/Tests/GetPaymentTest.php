<?php

namespace App\Modules\Payment\Tests;

use App\Modules\Payment\Payment;
use App\Modules\User\User;
use Tests\TestCase;

class GetPaymentTest extends TestCase
{
    public function test_get_payment()
    {
        $user = User::factory()->create();

        $payment = Payment::factory()->create([
            'creator_id' => $user,
            'gateway' => 'payjs',
            'gateway_number' => '1234567890',
        ]);

        $this->actingAs($user)
            ->getJson("/api/payments/$payment->id")
            ->assertSuccessful();
    }
}
