<?php

namespace App\Modules\Payment\Jobs;

use App\Modules\Payment\Actions\MarkPaymentAsExpired as MarkPaymentAsExpiredAction;
use App\Modules\Payment\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkPaymentAsExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Payment $payment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MarkPaymentAsExpiredAction::run($this->payment);
    }
}
