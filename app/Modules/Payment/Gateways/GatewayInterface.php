<?php

namespace App\Modules\Payment\Gateways;

interface GatewayInterface
{
    public function native(array $parameters): array;

    public function getName(): string;

    public function getTtl(): int;

    public function isPaid(string $number): bool;

    public function isValidSign(array $parameters): bool;

    public function resolveNumber(array $parameters): string;

    public function resolveContext(array $parameters): array;
}
