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
            'phone_number' => '+86:1860000'.mt_rand(1000, 9999),
            'referral_code' => Str::lower(Str::random(6)),
            'state' => UserState::ACTIVATED,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            event(new UserActivated($user));
        });
    }

    public function modelName()
    {
        return User::class;
    }
}
