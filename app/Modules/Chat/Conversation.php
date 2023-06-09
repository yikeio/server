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
 * @property string                                                              $id
 * @property string                                                              $title
 * @property string                                                              $prompt_id
 * @property int                                                                 $messages_count
 * @property int                                                                 $tokens_count
 * @property \Carbon\Carbon                                                      $first_active_at
 * @property \Carbon\Carbon                                                      $last_active_at
 * @property string                                                              $deleted_at
 * @property string                                                              $created_at
 * @property string                                                              $updated_at
 * @property string                                                              $creator_id
 * @property Prompt                                                              $prompt
 * @property \Illuminate\Database\Eloquent\Collection<\App\Modules\Chat\Message> $messages
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
