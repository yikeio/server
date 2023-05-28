<?php

use App\Modules\Prompt\Prompt;
use App\Modules\Tag\Tag;

class CreatePromptTest extends \Tests\TestCase
{
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
