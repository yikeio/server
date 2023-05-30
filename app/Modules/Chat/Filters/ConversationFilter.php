<?php

namespace App\Modules\Chat\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ConversationFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function prompt($promptId): ConversationFilter
    {
        return $this->where('prompt_id', $promptId);
    }

    protected function getSortableFields(): array
    {
        return ['id', 'last_active_at'];
    }
}
