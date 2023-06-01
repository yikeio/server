<?php

namespace App\Modules\Quota\Filters;

use App\Modules\Common\Actions\ConvertStringToArray;
use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class QuotaFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id'];
    }

    public function states(string $states): self
    {
        return $this->whereIn('state', ConvertStringToArray::run($states));
    }
}
