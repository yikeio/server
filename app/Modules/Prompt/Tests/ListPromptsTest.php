<?php

namespace App\Modules\Prompt\Tests;

use App\Modules\Prompt\Prompt;
use App\Modules\Tag\Tag;
use Tests\TestCase;

class ListPromptsTest extends TestCase
{
    public function test_user_can_list_prompts()
    {
        Prompt::factory(10)->create();

        $this->getJson(route('prompts.index'))
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }

    public function test_user_can_filter_prompts_by_tag_id()
    {
        $prompt1 = Prompt::factory()->create(['name' => 'Prompt 1']);
        $prompt2 = Prompt::factory()->create(['name' => 'Prompt 2']);
        $prompt3 = Prompt::factory()->create(['name' => 'Prompt 3']);

        $tag1 = Tag::factory()->create(['name' => 'Tag 1']);
        $tag2 = Tag::factory()->create(['name' => 'Tag 2']);
        $tag3 = Tag::factory()->create(['name' => 'Tag 3']);

        $prompt1->tags()->sync([$tag1->id]);
        $prompt2->tags()->sync([$tag2->id, $tag3->id]);
        $prompt3->tags()->sync([$tag1->id, $tag2->id, $tag3->id]);

        // Filter by tag1: $prompt1, $prompt3
        $this->getJson(route('prompts.index', ['tag' => $tag1->id]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => (int) $prompt1->id])
            ->assertJsonFragment(['id' => (int) $prompt3->id]);

        // Filter by tag2: $prompt2, $prompt3
        $this->getJson(route('prompts.index', ['tag' => $tag2->id]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => (int) $prompt2->id])
            ->assertJsonFragment(['id' => (int) $prompt3->id]);

        // Filter by tag3: $prompt2, $prompt3
        $this->getJson(route('prompts.index', ['tag' => $tag3->id]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => (int) $prompt2->id])
            ->assertJsonFragment(['id' => (int) $prompt3->id]);

        // Filter by tag1, tag2: $prompt1, $prompt2, $prompt3
        $this->getJson(route('prompts.index', ['tag' => [$tag1->id, $tag2->id]]))
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['id' => (int) $prompt1->id])
            ->assertJsonFragment(['id' => (int) $prompt2->id])
            ->assertJsonFragment(['id' => (int) $prompt3->id]);
    }
}
