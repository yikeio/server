<?php

namespace App\Modules\Quota;

use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotaFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'is_available' => random_int(0, 1),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'tokens_count' => random_int(10000, 1000000),
            'used_tokens_count' => random_int(0, 1000000),
        ];
    }

    public function modelName()
    {
        return Quota::class;
    }
}
