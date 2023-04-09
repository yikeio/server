<?php

namespace App\Modules\Common\Traits;

use App\Modules\Common\Actions\ConvertStringToArray;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Sortable
{
    protected function getSortableChineseFields(): array
    {
        return [];
    }

    protected function getSortableFields(): array
    {
        return [];
    }

    protected function getSortableFieldAliases(): array
    {
        return [];
    }

    protected function getSortableFieldAlias(string $sortable): ?string
    {
        return Arr::get($this->getSortableFieldAliases(), $sortable);
    }

    public function sorts($sorts): self
    {
        $sorts = ConvertStringToArray::run($sorts);

        foreach ($sorts as $sort) {
            $sort = explode(':', $sort);

            if (2 !== count($sort)) {
                continue;
            }

            [$field, $direction] = $sort;

            $field = $this->getSortableFieldAlias($field) ?? $field;

            if (! in_array($field, $this->getSortableFields())) {
                continue;
            }

            if (! in_array(Str::lower($direction), ['asc', 'desc'])) {
                continue;
            }

            if (! in_array($field, $this->getSortableChineseFields())) {
                $this->orderBy($field, $direction);

                continue;
            }

            $this->orderByRaw("CONVERT($field USING gbk) $direction");
        }

        return $this;
    }
}
