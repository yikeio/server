<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;

class ListPrompts
{
    public function __invoke()
    {
        return Prompt::simplePaginate(100);
    }
}
