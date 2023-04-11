<?php

namespace App\Modules\User;

use App\Modules\Chat\Conversation;
use App\Modules\Payment\Payment;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Quota\Quota;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\Events\UserCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            event(new UserCreated($user));
        });
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'creator_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'creator_id', 'id');
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(Quota::class, 'user_id', 'id');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'user_id', 'id');
    }

    public function getQuota(QuotaType $type): Quota|Model|null
    {
        return $this->quotas()
            ->where('type', $type)
            ->where('is_available', true)
            ->orderByDesc('id')
            ->first();
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
