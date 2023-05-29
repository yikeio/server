<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Tag\Tag;
use Illuminate\Http\Request;

class CreateTag
{
    public function __invoke(Request $request): Tag
    {
        return Tag::create($request->all())->refresh();
    }
}
