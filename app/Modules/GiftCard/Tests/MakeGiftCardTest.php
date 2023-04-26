<?php

namespace App\Modules\GiftCard\Tests;

use Tests\TestCase;

class MakeGiftCardTest extends TestCase
{
    public function test_system_can_create_gift_card()
    {
        $this->artisan('make:gift-card', [
            'name' => '好友礼品卡',
            '--tokens_count' => 10,
            '--days' => 30,
        ])->assertExitCode(0);

        $this->assertDatabaseHas('gift_cards', [
            'name' => '好友礼品卡',
            'tokens_count' => 10,
            'days' => 30,
        ]);
    }
}
