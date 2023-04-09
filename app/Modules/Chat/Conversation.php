<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Filters\ConversationFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    public $incrementing = false;

    protected $fillable = [
        'creator_id',
        'title',
        'messages_count',
        'tokens_count',
        'first_active_at',
        'last_active_at',
    ];

    protected $casts = [
        'id' => 'string',
        'creator_id' => 'string',
        'first_active_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    protected static function newFactory(): ConversationFactory
    {
        return ConversationFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return ConversationFilter::class;
    }
}
