<?php

namespace App\Modules\Prompt\Tests;

use App\Modules\Prompt\Prompt;
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
}
