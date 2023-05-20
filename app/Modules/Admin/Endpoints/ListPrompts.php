<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;

class ListPrompts
{
    public function __invoke()
    {
        return Prompt::paginate(15);
    }
}
