<?php

namespace App\Modules\Quota\Jobs;

use App\Modules\Quota\Enums\QuotaState;
use App\Modules\Quota\Quota;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshQuota implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Quota $quota)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $usedTokensCount = $this->quota->usages()->sum('tokens_count');

        $this->quota->used_tokens_count = $usedTokensCount;
        $availableTokensCount = $this->quota->tokens_count - $usedTokensCount;

        if ($availableTokensCount <= 0) {
            $this->quota->state = QuotaState::USED;
        }

        $this->quota->save();
    }
}
