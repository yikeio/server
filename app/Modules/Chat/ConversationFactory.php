<?php

namespace App\Modules\Chat;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
        ];
    }

    public function modelName()
    {
        return Conversation::class;
    }
}
