<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\Gateway;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'number' => now()->format('ymdHis').random_int(10000, 99999),
            'title' => $this->faker->name,
            'gateway' => Gateway::PAYJS,
            'gateway_number' => Str::random(20),
            'state' => PaymentState::PENDING,
            'creator_id' => fn () => User::factory()->create()->id,
            'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function ofUser(User $user)
    {
        return $this->state([
            'creator_id' => $user->id,
        ]);
    }

    public function pending()
    {
        return $this->state([
            'state' => PaymentState::PENDING,
        ]);
    }

    public function expired()
    {
        return $this->state([
            'state' => PaymentState::EXPIRED,
        ]);
    }

    public function paid()
    {
        return $this->state([
            'state' => PaymentState::PAID,
        ]);
    }
}
