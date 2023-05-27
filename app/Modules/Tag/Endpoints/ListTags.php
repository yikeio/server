<?php

namespace App\Modules\Tag\Endpoints;

use App\Modules\Tag\Tag;
use Illuminate\Http\Request;

class ListTags
{
    public function __invoke(Request $request)
    {
        return Tag::query()->simplePaginate(50);
    }
}
