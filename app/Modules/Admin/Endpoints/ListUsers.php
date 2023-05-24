<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\User\User;
use Illuminate\Http\Request;

class ListUsers
{
    public function __invoke(Request $request)
    {
        return tap(User::query()->with(['referrer'])->latest('created_at')->filter($request->query())->paginate(15), function ($resource) {
            $resource->makeVisible(['phone_number', 'first_active_at', 'last_active_at', 'paid_total']);
        });
    }
}
