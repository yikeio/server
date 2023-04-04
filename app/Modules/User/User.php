<?php

namespace App\Modules\User;

use App\Modules\Chat\Conversation;
use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasSnowflakes;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'root_referrer_id',
        'referrer_id',
        'level',
        'referrer_path',
        'referral_code',
        'referrals_count',
        'name',
        'phone_number',
        'is_admin',
        'first_active_at',
        'last_active_at',
    ];

    protected $hidden = [
        'phone_number',
    ];

    protected $casts = [
        'id' => 'string',
        'root_referrer_id' => 'string',
        'referrer_id' => 'string',
        'is_admin' => 'boolean',
        'first_active_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'creator_id', 'id');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
