<?php

namespace App\Modules\Quota\Tests;

use Tests\TestCase;

class ListPricingsTest extends TestCase
{
    public function test_list_pricings()
    {
        $this->getJson('/api/pricings')
            ->assertSuccessful();
    }
}
