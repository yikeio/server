<?php

namespace App\Modules\User\Filters;

use App\Modules\Common\Traits\Sortable;
use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    use Sortable;

    public $relations = [];

    protected function getSortableFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'first_active_at', 'last_active_at', 'paid_total', 'referrals_count'];
    }

    public function search(string $keywords): UserFilter
    {
        if (empty(trim($keywords))) {
            return $this;
        }

        return $this->where(function ($query) use ($keywords) {
            $query->where('name', 'like', "%{$keywords}%")
                ->orWhere('phone_number', 'like', "%{$keywords}%")
                ->orWhere('email', 'like', "%{$keywords}%");
        });
    }
}
