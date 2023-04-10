<?php

namespace App\Modules\Payment\Command;

use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Payment;
use Illuminate\Console\Command;

class CheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yike:check-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查支付单是否已经过期';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Payment::query()
            ->where('expired_at', '<', now())
            ->where('state', PaymentState::PENDING)
            ->update(['state' => PaymentState::EXPIRED]);
    }
}
