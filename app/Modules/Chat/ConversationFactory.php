<?php

namespace App\Modules\Chat;

use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        if (User::count() > 10) {
            $creator = User::inRandomOrder()->first();
        } else {
            $creator = User::factory()->create();
        }

        return [
            'creator_id' => $creator->id,
            'title' => $this->faker->title,
            'messages_count' => random_int(0, 6000),
            'tokens_count' => random_int(1000, 100000),
            'first_active_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
            'last_active_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function modelName()
    {
        return Conversation::class;
    }
}
