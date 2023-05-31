<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Filters\ConversationFilter;
use App\Modules\Prompt\Prompt;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\BelongsToCreator;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Illuminate\Support\Collection<\App\Modules\Chat\Message> $messages
 */
class Conversation extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use SoftDeletes;
    use Filterable;
    use BelongsToCreator;

    protected $fillable = [
        'creator_id',
        'title',
        'prompt_id',
        'messages_count',
        'tokens_count',
        'first_active_at',
        'last_active_at',
    ];

    protected $casts = [
        'id' => 'string',
        'creator_id' => 'string',
        'prompt_id' => 'string',
        'first_active_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class, 'prompt_id', 'id')->withTrashed();
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
