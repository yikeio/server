<?php

namespace App\Modules\GiftCard;

use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'tokens_count' => random_int(1, 10000),
            'days' => random_int(1, 1000),
            'expired_at' => now()->addYear(),
            'creator_id' => 1,
        ];
    }
}
