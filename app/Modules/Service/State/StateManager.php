<?php

namespace App\Modules\Service\State;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Str;

class StateManager
{
    protected int $ttl = 3600;

    public function __construct(protected Repository $repository)
    {
    }

    public function create(mixed $value): string
    {
        $stateKey = sha1(Str::random());

        $this->repository->put($this->getCacheKey($stateKey), $value, $this->ttl);

        return $stateKey;
    }

    public function get(string $stateKey): mixed
    {
        return $this->repository->get($this->getCacheKey($stateKey));
    }

    protected function getCacheKey(string $stateKey): string
    {
        return "state_{$stateKey}";
    }

    public function setTtl(int $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }
}
