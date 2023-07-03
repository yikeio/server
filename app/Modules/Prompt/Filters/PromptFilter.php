<?php

namespace App\Modules\Prompt\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class PromptFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'sort_order'];
    }

    public function tag($ids): RewardFilter
    {
        return $this->related('tags', function ($query) use ($ids) {
            return $query->whereIn('tag_id', (array) $ids);
        });
    }
}
