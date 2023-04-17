<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Filters\MessageFilter;
use App\Modules\Quota\Tokenizable;
use App\Modules\Quota\TokenizableInterface;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model implements TokenizableInterface
{
    use HasSnowflakes;
    use HasFactory;
    use SoftDeletes;
    use Filterable;
    use Tokenizable;

    public $incrementing = false;

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'raws',
        'tokens_count',
        'creator_id',
        'quota_id',
    ];

    protected $casts = [
        'id' => 'string',
        'conversation_id' => 'string',
        'role' => MessageRole::class,
        'raws' => 'array',
        'creator_id' => 'string',
        'quota_id' => 'string',
    ];

    protected $hidden = [
        'raws',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return MessageFilter::class;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    public function getQuotaId(): int
    {
        return $this->quota_id;
    }

    public function getMorphClass(): string
    {
        return $this->getTable();
    }
}
