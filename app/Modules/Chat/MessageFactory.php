<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Quota\Quota;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        $content = $this->faker->text;
        $conversation = Conversation::query()->inRandomOrder()->first() ?? Conversation::factory()->create();

        return [
            'role' => MessageRole::USER,
            'content' => $content,
            'conversation_id' => $conversation->id,
            'tokens_count' => strlen($content) * 3,
            'creator_id' => $conversation->creator_id,
            'quota_id' => Quota::where('user_id', $conversation->creator_id)->inRandomOrder()->first()?->id ?? Quota::factory()->create()->id,
        ];
    }

    public function modelName()
    {
        return Message::class;
    }
}
