<?php

namespace App\Modules\Chat\Events;

use App\Modules\Chat\Completion;
use App\Modules\Quota\ConsumeQuotaEvent;
use App\Modules\Quota\Quota;
use App\Modules\Service\OpenAI\Tokenizer;
use App\Modules\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompletionCreated implements ConsumeQuotaEvent, ShouldQueue
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected array $usage;

    public function __construct(protected Completion $completion, protected Quota $quota)
    {
    }

    public function getCreator(): User
    {
        return $this->completion->getCreator();
    }

    public function getQuota(): Quota
    {
        return $this->quota;
    }

    public function getTokensCount(): int
    {
        return $this->getUsage()['tokens_count'] ?? 0;
    }

    public function getUsage(): array
    {
        if (! empty($this->usage)) {
            return $this->usage;
        }

        /** @var Tokenizer $tokenizer */
        $tokenizer = app(Tokenizer::class);
        $tokenizer->setModel(config('openai.chat.model'));

        $this->usage = $tokenizer->predictUsage($this->completion->getPrompts(), $this->completion->getValue());

        return $this->usage;
    }

    public function getCompletion(): Completion
    {
        return $this->completion;
    }
}
