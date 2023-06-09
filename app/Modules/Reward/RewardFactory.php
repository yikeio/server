<?php

namespace App\Modules\Reward;

use App\Modules\Payment\Payment;
use App\Modules\Reward\Enums\RewardState;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    protected $model = Reward::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(1, 100),
            'state' => RewardState::UNWITHDRAWN,
        ];
    }

    public function payment(Payment $payment): RewardFactory
    {
        return $this->state(function (array $attributes) use ($payment) {
            return [
                'payment_id' => $payment->id,
                'amount' => $payment->amount * $attributes['rate'] / 100,
            ];
        });
    }

    public function withdrawn(): RewardFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => RewardState::UNWITHDRAWN,
                'withdrawn_at' => now(),
            ];
        });
    }

    public function unwithdrawn(): RewardFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => RewardState::UNWITHDRAWN,
                'withdrawn_at' => null,
            ];
        });
    }

    public function withdrawnWithAmount(int $amount): RewardFactory
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'state' => RewardState::WITHDRAWN,
                'amount' => $amount,
                'withdrawn_at' => now(),
            ];
        });
    }

    public function toUser(User $user): RewardFactory
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    public function fromUser(User $user): RewardFactory
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'from_user_id' => $user->id,
            ];
        });
    }

    public function rate(int $rate): RewardFactory
    {
        return $this->state(function (array $attributes) use ($rate) {
            return [
                'rate' => $rate,
            ];
        });
    }
}
