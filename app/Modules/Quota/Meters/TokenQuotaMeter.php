<?php

namespace App\Modules\Quota\Meters;

class TokenQuotaMeter implements QuotaMeterInterface
{
    protected int $tokensCount;

    protected int $usedTokensCount;

    protected int $availableTokensCount;

    public function __construct(array $usage = [])
    {
        $this->tokensCount = $usage['tokens_count'] ?? 0;
        $this->usedTokensCount = $usage['used_tokens_count'] ?? 0;
        $this->availableTokensCount = $usage['available_tokens_count'] ?? 0;

        return $this;
    }

    public function consume(int $tokensCount): static
    {
        $this->usedTokensCount += $tokensCount;
        $this->availableTokensCount -= $tokensCount;

        return $this;
    }

    public function recharge(int $tokensCount): static
    {
        $this->availableTokensCount += $tokensCount;
        $this->tokensCount += $tokensCount;

        return $this;
    }

    public function reset(): static
    {
        $this->usedTokensCount = 0;
        $this->availableTokensCount = $this->tokensCount;

        return $this;
    }

    public function getBalance(): bool
    {
        return $this->availableTokensCount;
    }

    public function getUsage(): array
    {
        return [
            'tokens_count' => $this->tokensCount,
            'used_tokens_count' => $this->usedTokensCount,
            'available_tokens_count' => $this->availableTokensCount,
        ];
    }
}
