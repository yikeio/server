<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\User\User;

class ListUsers
{
    public function __invoke()
    {
        return tap(User::query()->with(['referrer'])->paginate(15), function ($resource) {
            $resource->makeVisible(['phone_number', 'first_active_at', 'last_active_at', 'paid_total']);
        });
    }
}
