<?php

namespace App\Modules\Quota;

trait Tokenizable
{
    public function getTokenizableId(): int
    {
        return $this->id;
    }

    public function getTokenizableType(): string
    {
        return $this->getMorphClass();
    }

    public function getTokensCount(): int
    {
        return $this->tokens_count ?? 0;
    }
}
