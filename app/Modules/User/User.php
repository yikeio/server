<?php

namespace App\Modules\User;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\Payment\Payment;
use App\Modules\Quota\Quota;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\Enums\SettingKey;
use App\Modules\User\Enums\UserState;
use App\Modules\User\Events\UserCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * @property UserState $state
 * @property int       $id
 * @property string     $referral_code
 */
class User extends Authenticatable
{
    use HasSnowflakes;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use SoftDeletes;

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
        'state',
        'paid_total',
        'avatar',
    ];

    protected $appends = [
        'has_paid',
        'referral_url',
    ];

    protected $hidden = [
        'phone_number',
        'paid_total',
    ];

    protected $casts = [
        'id' => 'string',
        'root_referrer_id' => 'string',
        'referrer_id' => 'string',
        'is_admin' => 'boolean',
        'first_active_at' => 'datetime',
        'last_active_at' => 'datetime',
        'state' => UserState::class,
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'creator_id', 'id');
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

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'user_id', 'id');
    }

    public function getHasPaidAttribute(): bool
    {
        return $this->paid_total > 0;
    }

    public function getReferralCodeAttribute(string $referralCode): string
    {
        return Str::upper($referralCode);
    }

    public function getReferralUrlAttribute(): string
    {
        return url('/?referrer='.$this->referral_code);
    }

    public function getSetting(SettingKey $key): mixed
    {
        $value = $this->settings()->where('key', $key)->first(['value']);

        return ! empty($value) ? $value->value : Arr::get(SettingKey::defaults(), $key->value);
    }

    public function getAvailableQuota(): ?Quota
    {
        /** @var Quota $quota */
        $quota = $this->quotas()
            ->where('is_available', true)
            ->orderByDesc('id')
            ->first();

        return $quota;
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
