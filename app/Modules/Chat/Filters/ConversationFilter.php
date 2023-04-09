<?php

namespace App\Modules\Chat\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ConversationFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'last_active_at'];
    }
}
