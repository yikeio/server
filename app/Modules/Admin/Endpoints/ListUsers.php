<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use App\Modules\User\User;

class ListUsers
{
    public function __invoke()
    {
        return tap(User::query()->with(['referrer'])->paginate(15), fn($resource) => $resource->makeVisible('phone_number'));
    }
}
