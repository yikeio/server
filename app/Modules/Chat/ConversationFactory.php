<?php

namespace App\Modules\Chat;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'messages_count' => random_int(0, 6000),
            'tokens_count' => random_int(1000, 100000),
            'first_active_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
            'last_active_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function modelName(): string
    {
        return Conversation::class;
    }
}
