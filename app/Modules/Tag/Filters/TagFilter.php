<?php

namespace App\Modules\Tag\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class TagFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public function search(string $keywords): TagFilter
    {
        if (empty(trim($keywords))) {
            return $this;
        }

        return $this->where(function ($query) use ($keywords) {
            $query->where('name', 'like', "%{$keywords}%");
        });
    }
}
