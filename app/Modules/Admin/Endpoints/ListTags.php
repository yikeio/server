<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Tag\Tag;
use Illuminate\Http\Request;

class ListTags
{
    public function __invoke(Request $request)
    {
        return Tag::filter($request->query())->paginate(15);
    }
}
