<?php

namespace App\Modules\Sms\Tests;

use App\Modules\Sms\Enums\VerificationCodeScene;
use App\Modules\Sms\VerificationCode;
use Mockery\MockInterface;
use Tests\TestCase;

class SendVerificationCodeTest extends TestCase
{
    public function test_send_verification_code_with_invalid_phone_number()
    {
        $this->withoutMiddleware()->postJson('/api/sms/verification-codes:send', [
            'phone_number' => '+86:88888888888',
            'scene' => VerificationCodeScene::REGISTER,
        ])->assertStatus(422);
    }

    public function test_send_verification_code_successfully()
    {
        $this->partialMock(VerificationCode::class, function (MockInterface $mock) {
            $mock->makePartial()
                ->shouldReceive('send')
                ->andReturn(true);
        });

        $this->withoutMiddleware()->postJson('/api/sms/verification-codes:send', [
            'phone_number' => '+86:18600000000',
            'scene' => VerificationCodeScene::REGISTER,
        ])->assertSuccessful();
    }

    public function test_send_verification_code_failed()
    {
        $this->partialMock(VerificationCode::class, function (MockInterface $mock) {
            $mock->makePartial()
                ->shouldReceive('send')
                ->andReturn(false);
        });

        $this->withoutMiddleware()->postJson('/api/sms/verification-codes:send', [
            'phone_number' => '+86:18600000000',
            'scene' => VerificationCodeScene::REGISTER,
        ])
            ->assertServerError()
            ->assertJsonFragment([
                'message' => '发送失败，请稍后再试',
            ]);
    }
}
