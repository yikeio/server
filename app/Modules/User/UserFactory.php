<?php

namespace App\Modules\User;

use App\Modules\User\Enums\UserState;
use App\Modules\User\Events\UserActivated;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone_number' => '+86:186'.mt_rand(10000000, 99999999),
            'referral_code' => Str::lower(Str::random(6)),
            'email' => $this->faker->unique()->safeEmail,
            'state' => UserState::ACTIVATED,
            'paid_total' => $this->faker->randomFloat(2, 0, 100000),
            'referrals_count' => random_int(0, 10000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            event(new UserActivated($user));
        });
    }

    public function unactivated(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => UserState::UNACTIVATED,
            ];
        })->afterCreating(function (User $user) {
            $user->getAvailableQuota()->update([
                'is_available' => false,
            ]);
        });
    }

    public function modelName()
    {
        return User::class;
    }
}
