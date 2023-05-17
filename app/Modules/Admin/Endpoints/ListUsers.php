<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use App\Modules\User\User;

class ListUsers
{
    public function __invoke()
    {
        return User::with(['referrer'])->paginate(15);
    }
}
