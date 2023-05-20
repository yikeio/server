<?php

namespace App\Modules\GiftCard;

use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'tokens_count' => random_int(1, 10000),
            'days' => random_int(1, 30),
            'expired_at' => now()->addYear(),
            'creator_id' => 1,
            'user_id' => random_int(0, 1) ? fn () => User::factory()->create()->id : 0,
            'used_at' => [null, $this->faker->dateTimeBetween('-5 month', 'now')][random_int(0, 1)],
            'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
