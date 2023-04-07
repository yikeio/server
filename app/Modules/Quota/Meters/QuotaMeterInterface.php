<?php

namespace App\Modules\Quota\Meters;

interface QuotaMeterInterface
{
    public function consume(int $tokensCount): static;

    public function recharge(int $tokensCount): static;

    public function reset(): static;

    public function getBalance(): int;

    public function getUsage(): array;

    public function setUsage(array $usage): static;
}
