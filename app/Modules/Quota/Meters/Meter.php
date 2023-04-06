<?php

namespace App\Modules\Quota\Meters;

interface Meter
{
    public function consume(int $tokensCount): static;

    public function recharge(int $tokensCount): static;

    public function reset(): static;

    public function getBalance(): bool;

    public function getUsage(): array;
}
