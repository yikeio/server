<?php

namespace App\Modules\Payment\Tests;

use App\Modules\Payment\Gateways\GatewayInterface;
use App\Modules\Payment\Payment;
use App\Modules\Payment\Processors\GrantQuotaProcessor;
use App\Modules\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessPaymentTest extends TestCase
{
    public function test_process_payment()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $user->quotas()->update(['is_available' => false]);

        /** @var Payment $payment */
        $payment = Payment::factory()->create([
            'creator_id' => $user->id,
            'gateway' => 'payjs',
            'gateway_number' => '1234567890',
            'processors' => [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'quota_type' => 'chat',
                        'quota_meter' => 'token',
                        'tokens_count' => 300 * 1000,
                        'days' => 7,
                    ],
                ],
            ],
        ]);

        $this->assertFalse($payment->state->isPaid());

        $this->partialMock(GatewayInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isValidSign')->andReturnTrue();
            $mock->shouldReceive('getName')->andReturn('payjs');
            $mock->shouldReceive('resolveNumber')->andReturn('1234567890');
            $mock->shouldReceive('isPaid')->andReturnTrue();
        });

        $this->post('/api/payments:process', [
            'payjs_order_id' => '1234567890',
        ])
            ->assertSuccessful()
            ->assertContent('success');

        $this->assertTrue($payment->fresh()->state->isPaid());

        $this->assertCount(2, $user->quotas()->get());
    }
}
