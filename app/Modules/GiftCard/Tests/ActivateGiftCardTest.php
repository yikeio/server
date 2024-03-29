<?php

namespace App\Modules\GiftCard\Tests;

use App\Modules\GiftCard\GiftCard;
use App\Modules\User\User;
use Tests\TestCase;

class ActivateGiftCardTest extends TestCase
{
    public function test_user_can_activate_gift_card()
    {
        $giftCard = GiftCard::factory()->unused()->create([
            'name' => '测试礼品卡',
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/gift-cards:activate', [
            'code' => $giftCard->code,
        ])->assertOk();
    }
}
