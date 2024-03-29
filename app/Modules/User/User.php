<?php

namespace App\Modules\User;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\Payment\Payment;
use App\Modules\Prompt\Prompt;
use App\Modules\Quota\Enums\QuotaState;
use App\Modules\Quota\Quota;
use App\Modules\Reward\Enums\RewardState;
use App\Modules\Reward\Reward;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\Actions\GetUserRewardsTotal;
use App\Modules\User\Actions\GetUserUnwithdrawnRewardsTotal;
use App\Modules\User\Enums\SettingKey;
use App\Modules\User\Enums\UserState;
use App\Modules\User\Events\UserCreated;
use App\Modules\User\Filters\UserFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Overtrue\LaravelLike\Traits\Liker;

/**
 * @property int                              $id
 * @property string                           $referral_code
 * @property int                              $root_referrer_id
 * @property int                              $referrer_id
 * @property int                              $level
 * @property string                           $referrer_path
 * @property int                              $referrals_count
 * @property string                           $name
 * @property string                           $phone_number
 * @property bool                             $is_admin
 * @property UserState                        $state
 * @property \Carbon\Carbon                   $first_active_at
 * @property \Carbon\Carbon                   $last_active_at
 * @property int                              $paid_total
 * @property string                           $avatar
 * @property \App\Modules\User\User           $referrer
 * @property \App\Modules\User\User           $rootReferrer
 * @property \App\Modules\User\User[]         $referrals
 * @property \App\Modules\Reward\Reward[]     $rewards
 * @property \App\Modules\Quota\Quota[]       $quotas
 * @property \App\Modules\Payment\Payment[]   $payments
 * @property \App\Modules\Chat\Conversation[] $conversations
 * @property \App\Modules\Chat\Message[]      $messages
 * @property \App\Modules\Prompt\Prompt[]     $prompts
 * @property \Carbon\Carbon                   $created_at
 * @property \Carbon\Carbon                   $updated_at
 * @property \Carbon\Carbon                   $deleted_at
 */
class User extends Authenticatable
{
    use HasSnowflakes;
    use HasApiTokens;
    use Notifiable;
    use Filterable;
    use HasFactory;
    use SoftDeletes;
    use Liker;

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

    protected $safeFields = [
        'id',
        'name',
        'avatar',
        'referrals_count',
        'is_admin',
        'state',
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

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id')->withTrashed();
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'creator_id', 'id');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class, 'user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'creator_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'creator_id', 'id');
    }

    public function prompts(): HasMany
    {
        return $this->hasMany(Prompt::class, 'creator_id', 'id');
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

    public function getRewardsTotalAttribute()
    {
        return GetUserRewardsTotal::run($this);
    }

    public function getUnwithdrawnRewardsTotalAttribute()
    {
        return GetUserUnwithdrawnRewardsTotal::run($this);
    }

    public function getReferralCodeAttribute(string $referralCode): string
    {
        return Str::upper($referralCode);
    }

    public function getReferralUrlAttribute(): string
    {
        return config('app.url').'/?referrer='.$this->referral_code;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function getSetting(SettingKey $key): mixed
    {
        $value = $this->settings()->where('key', $key)->first(['value']);

        return ! empty($value) ? $value->value : Arr::get(SettingKey::defaults(), $key->value);
    }

    public function getUsingQuota(): ?Quota
    {
        /** @var Quota $quota */
        $quota = $this->quotas()
            ->where('state', QuotaState::USING)
            ->first();

        return $quota;
    }

    public function onlySafeFields(): array
    {
        return $this->only($this->safeFields);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return UserFilter::class;
    }
}
