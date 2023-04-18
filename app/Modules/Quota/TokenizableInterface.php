<?php

namespace App\Modules\Quota;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface TokenizableInterface
{
    public function getTokensCount(): int;

    public function quota(): BelongsTo;

    public function getQuotaId(): int;

    public function creator(): BelongsTo;

    public function getCreatorId(): int;

    public function getTokenizableId(): int;

    public function getTokenizableType(): string;
}
