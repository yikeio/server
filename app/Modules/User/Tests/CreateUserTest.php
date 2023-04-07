<?php

namespace App\Modules\User\Tests;

use App\Modules\Sms\VerificationCode;
use App\Modules\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test_create_user_with_referral_code()
    {
        $this->partialMock(VerificationCode::class, function (MockInterface $mock) {
            $mock->makePartial()
                ->shouldReceive('check')
                ->andReturn(true);
        });

        $referrer = User::factory()->create();

        $this->postJson('/api/users', [
            'phone_number' => '+86:18600000000',
            'referral_code' => $referrer->referral_code,
            'sms_verification_code' => strval(mt_rand(1000, 9999)),
        ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => '用户-0000',
            ])
            ->assertJsonIsObject('user')
            ->assertJsonIsObject('token')
            ->assertJsonFragment([
                'referrer_id' => $referrer->id,
                'root_referrer_id' => $referrer->id,
                'referrer_path' => $referrer->id,
            ]);
    }
}
