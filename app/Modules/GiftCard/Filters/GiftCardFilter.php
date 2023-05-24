<?php

namespace App\Modules\GiftCard\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class GiftCardFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'used_at', 'expired_at', 'tokens_count', 'days'];
    }
}
