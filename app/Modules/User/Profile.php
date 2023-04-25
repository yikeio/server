<?php

namespace App\Modules\User;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property User $user
 */
class Profile extends Model
{
    use HasSnowflakes;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'platform',
        'open_id',
        'nickname',
        'name',
        'avatar',
        'raw',
    ];

    protected $hidden = [
        'raw',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'open_id' => 'string',
        'raw' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
