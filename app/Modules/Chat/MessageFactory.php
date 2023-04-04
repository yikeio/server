<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Enums\MessageRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'role' => MessageRole::USER,
            'content' => $this->faker->text,
        ];
    }

    public function modelName()
    {
        return Message::class;
    }
}
