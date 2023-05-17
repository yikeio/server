<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\Gateway;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'number' => now()->format('ymdHis').random_int(10000, 99999),
            'state' => PaymentState::PENDING,
            'title' => $this->faker->name,
            'gateway' => Gateway::PAYJS,
            'gateway_number' => Str::random(20),
            'creator_id' => fn () => User::factory()->create()->id,
        ];
    }

    public function modelName()
    {
        return Payment::class;
    }
}
