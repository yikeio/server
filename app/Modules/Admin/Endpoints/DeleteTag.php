<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Tag\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteTag
{
    public function __invoke(Request $request, Tag $tag): Response
    {
        $tag->delete();

        return response()->noContent();
    }
}
