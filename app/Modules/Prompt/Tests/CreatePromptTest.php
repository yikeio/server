<?php

use App\Modules\Prompt\Prompt;
use App\Modules\Tag\Tag;
use App\Modules\User\User;
use Tests\TestCase;

class CreatePromptTest extends TestCase
{
    public function test_create_prompt()
    {
        $user = User::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $tag3 = Tag::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route('prompts.store'), [
            'name' => 'test',
            'description' => 'description here',
            'logo' => 'logo here',
            'prompt_cn' => 'cn here',
            'prompt_en' => 'en here',
            'greeting' => 'greeting here',
            'tags' => [$tag1->id, $tag2->id],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('prompts', [
            'name' => 'test',
            'description' => 'description here',
            'logo' => 'logo here',
            'prompt_cn' => 'cn here',
            'prompt_en' => 'en here',
            'greeting' => 'greeting here',
        ]);
        $this->assertDatabaseHas('taggables', [
            'taggable_type' => Prompt::class,
            'taggable_id' => Prompt::first()->id,
            'tag_id' => $tag1->id,
        ]);
        $this->assertDatabaseHas('taggables', [
            'taggable_type' => Prompt::class,
            'taggable_id' => Prompt::first()->id,
            'tag_id' => $tag2->id,
        ]);
    }

    public function test_prompt_has_tags()
    {
        /** @var Prompt $prompt */
        $prompt = Prompt::factory()->create();

        $this->assertEmpty($prompt->tags);

        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $tag3 = Tag::factory()->create();

        $prompt->tags()->sync([$tag1->id, $tag2->id]);

        $this->assertCount(2, $prompt->fresh()->tags);
    }
}
