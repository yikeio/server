<?php

use App\Modules\Prompt\Prompt;
use App\Modules\User\User;
use Tests\TestCase;

class DeletePromptTest extends TestCase
{
    public function test_delete_prompt()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $prompt = Prompt::factory()->create([
            'creator_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson(route('prompts.destroy', $prompt->id));

        $response->assertNoContent();
        $this->assertSoftDeleted($prompt);

        // cannot delete other's prompt
        $prompt2 = Prompt::factory()->create([
            'creator_id' => $user2->id,
        ]);

        $this->deleteJson(route('prompts.destroy', $prompt2->id))
            ->assertForbidden();
    }
}
