<?php

namespace App\Modules\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'referral_code' => Str::lower(Str::random(6)),
        ];
    }

    public function modelName()
    {
        return User::class;
    }
}
