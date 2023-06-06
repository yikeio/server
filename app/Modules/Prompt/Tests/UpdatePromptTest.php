<?php

use App\Modules\Prompt\Prompt;
use App\Modules\Tag\Tag;
use App\Modules\User\User;
use Tests\TestCase;

class UpdatePromptTest extends TestCase
{
    public function test_update_prompt()
    {
        $user = User::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $tag3 = Tag::factory()->create();

        $this->actingAs($user);

        $prompt = Prompt::factory()->create([
            'creator_id' => $user->id,
        ]);

        $response = $this->patchJson(route('prompts.update', $prompt->id), [
            'name' => 'test',
            'description' => 'description here',
            'prompt_cn' => 'cn here',
            'prompt_en' => 'en here',
            'greeting' => 'greeting here',
            'tags' => [$tag1->id, $tag2->id],
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('prompts', [
            'name' => 'test',
            'description' => 'description here',
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

        // cannot use same name
        $prompt2 = Prompt::factory()->create([
            'creator_id' => $user->id,
        ]);
        $response = $this->patchJson(route('prompts.update', $prompt2->id), [
            'name' => 'test', // same name with $prompt
        ]);
        $response->assertBadRequest();
    }
}
