<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use App\Modules\Tag\Tag;
use Illuminate\Http\Request;

class UpdateTag
{
    public function __invoke(Request $request, Tag $tag): Tag
    {
        $tag->update($request->all());

        return $tag;
    }
}
