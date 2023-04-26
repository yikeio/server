<?php

namespace App\Modules\GiftCard\Tests;

use App\Modules\GiftCard\GiftCard;
use App\Modules\User\User;
use Tests\TestCase;

class ActivateGiftCardTest extends TestCase
{
    public function test_user_can_activate_gift_card()
    {
        $giftCard = GiftCard::factory()->create([
            'name' => '测试礼品卡',
            'tokens_count' => 10,
            'days' => 30,
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/gift-cards:activate', [
            'code' => $giftCard->code,
        ])->assertOk();
    }
}
