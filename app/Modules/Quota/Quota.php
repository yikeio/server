<?php

namespace App\Modules\Quota;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quota extends Model
{
    use HasSnowflakes;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'context',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
