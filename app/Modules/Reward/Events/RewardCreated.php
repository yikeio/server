<?php

namespace App\Modules\Reward\Events;

use App\Modules\Reward\Reward;

class RewardCreated
{
    public function __construct(public Reward $reward)
    {
    }
}
