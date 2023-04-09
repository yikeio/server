<?php

namespace App\Modules\Payment\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class PaymentFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    public function states(mixed $states): self
    {
        return $this->whereIn('state', $states);
    }

    protected function getSortableFields(): array
    {
        return ['id'];
    }
}
