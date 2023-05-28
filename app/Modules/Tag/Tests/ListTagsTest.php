<?php

namespace App\Modules\Tag\Tests;

use App\Modules\Tag\Tag;
use Tests\TestCase;

class ListTagsTest extends TestCase
{
    public function test_user_can_list_tags()
    {
        Tag::factory(10)->create();

        $this->getJson(route('tags.index'))
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
