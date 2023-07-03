<?php

namespace App\Modules\Reward\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class RewardFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'withdrawn_at'];
    }
}
