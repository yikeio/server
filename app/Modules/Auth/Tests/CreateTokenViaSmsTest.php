<?php

namespace App\Modules\Auth\Tests;

use App\Modules\Sms\VerificationCode;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateTokenViaSmsTest extends TestCase
{
    public function test_create_token_via_sms()
    {
        $this->partialMock(VerificationCode::class, function (MockInterface $mock) {
            $mock->makePartial()
                ->shouldReceive('check')
                ->andReturn(true);
        });

        $this->withoutMiddleware()->postJson('/api/auth/tokens:via-sms', [
            'phone_number' => '+86:18000000000',
            'sms_verification_code' => Str::random(6),
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'value',
                'type',
                'expires_at',
            ]);
    }
}
