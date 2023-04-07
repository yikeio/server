<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\PaymentState;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'number' => now()->format('ymdHis').random_int(1000, 9999),
            'state' => PaymentState::PENDING,
            'title' => $this->faker->sentence,
        ];
    }

    public function modelName()
    {
        return Payment::class;
    }
}
