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
            'phone_number' => '+86:1860000'.mt_rand(1000, 9999),
            'referral_code' => Str::lower(Str::random(6)),
        ];
    }

    public function modelName()
    {
        return User::class;
    }
}
