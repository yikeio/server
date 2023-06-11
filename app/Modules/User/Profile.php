<?php

namespace App\Modules\User;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $platform
 * @property string $open_id
 * @property string $nickname
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property array  $raw
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User   $user
 */
class Profile extends Model
{
    use HasSnowflakes;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'platform',
        'open_id',
        'nickname',
        'name',
        'email',
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
