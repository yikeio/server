<?php

namespace App\Modules\User;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasSnowflakes;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'raws',
    ];
}
