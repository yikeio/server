<?php

namespace App\Modules\Quota;

use App\Modules\Payment\Exceptions\InvalidArgumentException;
use App\Modules\Quota\Enums\QuotaMeter;
use App\Modules\Quota\Meters\QuotaMeterInterface;

class MeterManager
{
    public function get(string|QuotaMeter $meter): QuotaMeterInterface
    {
        if (is_string($meter)) {
            $meter = QuotaMeter::tryFrom($meter);
        }

        if (empty($meter)) {
            throw new InvalidArgumentException("[QUOTA] - Meter [$meter] not supported.");
        }

        return new ($meter->resolve());
    }
}
