<?php

namespace App\Modules\Reward\Tests;

use App\Modules\Payment\Payment;
use App\Modules\Reward\Reward;
use App\Modules\User\User;
use Tests\TestCase;

class ListRewardsTest extends TestCase
{
    public function test_user_can_list_himself_rewards()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $paymentOfUser = Payment::factory()->ofUser($user)->paid()->create([
            'amount' => 1000,
        ]);
        $paymentOfUser2 = Payment::factory()->ofUser($user2)->paid()->create([
            'amount' => 2000,
        ]);

        Reward::factory(3)->rate(10)->toUser($user)->payment($paymentOfUser)->create();
        Reward::factory(2)->rate(10)->toUser($user2)->payment($paymentOfUser2)->create();

        $this->actingAs($user)->getJson(route('rewards.index'))->assertSuccessful()->assertJsonCount(3, 'data');
        $this->actingAs($user2)->getJson(route('rewards.index'))->assertSuccessful()->assertJsonCount(2, 'data');
    }
}
