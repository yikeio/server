<?php

namespace App\Modules\Payment\Tests;

use App\Modules\Payment\Gateways\GatewayInterface;
use App\Modules\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    public function test_create_payment()
    {
        $this->partialMock(GatewayInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('native')
                ->andReturn([
                    'code_url' => 'weixin://wxpay/bizpayurl?pr=BAKUICyzz',
                    'out_trade_no' => 'xoicfsdjhik0puwrcsm2bkh7rjjhye7l',
                    'payjs_order_id' => '2023040713070800629817783',
                    'qrcode' => 'https://payjs.cn/qrcode/796hbk25uqekouclhg3hh48ojjr96wa91lqobqcmpulzkvqmbi==',
                    'return_code' => 1,
                    'return_msg' => 'SUCCESS',
                    'total_fee' => '1',
                    'sign' => '4857D095B87FED12F7F91F9D9515EE7C',
                ]);

            $mock->shouldIgnoreMissing();
        });

        /** @var User $user */
        $user = User::factory()->create();

        $user->quotas()->update(['is_available' => false]);

        $this->actingAs($user)
            ->postJson('/api/payments', [
                'quota_type' => 'chat',
                'pricing' => 'weekly',
            ])
            ->assertSuccessful();
    }
}
