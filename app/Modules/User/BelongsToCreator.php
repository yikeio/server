<?php

namespace App\Modules\User;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCreator
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function getCreatorId(): int
    {
        return $this->creator_id;
    }
}
