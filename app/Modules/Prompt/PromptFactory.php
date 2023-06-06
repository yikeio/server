<?php

namespace App\Modules\Prompt;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromptFactory extends Factory
{
    protected $model = Prompt::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'prompt_cn' => $this->faker->paragraph,
            'prompt_en' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(),
            'greeting' => $this->faker->paragraph,
            'description' => $this->faker->paragraph,
        ];
    }
}
