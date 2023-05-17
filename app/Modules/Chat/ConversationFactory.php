<?php

namespace App\Modules\Chat;

use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'creator_id' => fn () => User::factory()->create()->id,
            'title' => $this->faker->title,
            'messages_count' => random_int(0, 6000),
            'tokens_count' => random_int(1000, 100000),
            'first_active_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'last_active_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function modelName()
    {
        return Conversation::class;
    }
}
