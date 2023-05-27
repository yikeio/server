<?php

namespace App\Modules\Tag;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'sort_order' => random_int(0, 100),
        ];
    }
}
