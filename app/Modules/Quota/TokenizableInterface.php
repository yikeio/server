<?php

namespace App\Modules\Quota;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface TokenizableInterface
{
    public function getTokensCount(): int;

    public function quotaStatements(): MorphMany;

    public function quota(): BelongsTo;

    public function getQuotaId(): int;

    public function creator(): BelongsTo;

    public function getCreatorId(): int;
}
