<?php

namespace App\Modules\OAuth\Tests;

use App\Modules\Sms\VerificationCode;
use App\Modules\User\User;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateTokenViaSmsTest extends TestCase
{
    public function test_create_token_via_sms()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->mock(VerificationCode::class, function (MockInterface $mock) {
            $mock->makePartial()
                ->shouldReceive('check')
                ->andReturn(true);
        });

        $this->postJson('/api/oauth/tokens:via-sms', [
            'phone_number' => $user->phone_number,
            'sms_verification_code' => strval(mt_rand(1000, 9999)),
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'value',
                'type',
                'expires_at',
            ]);
    }
}
