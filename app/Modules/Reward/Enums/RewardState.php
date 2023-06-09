<?php

namespace App\Modules\Reward\Enums;

enum RewardState: string
{
    case UNWITHDRAWN = 'unwithdrawn';
    case WITHDRAWN = 'withdrawn';
}
