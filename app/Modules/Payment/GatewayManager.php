<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\Gateway;
use App\Modules\Payment\Exceptions\InvalidArgumentException;
use App\Modules\Payment\Gateways\GatewayInterface;

class GatewayManager
{
    public function __construct(protected array $config)
    {
    }

    public function get(string|Gateway $gateway): GatewayInterface
    {
        if (is_string($gateway)) {
            $gateway = Gateway::tryFrom($gateway);
        }

        if (empty($gateway)) {
            throw new InvalidArgumentException("[PAYMENT] - Gateway [$gateway] not supported.");
        }

        return new ($gateway->resolve())($this->config[$gateway->value]);
    }
}
