<?php

namespace App\Modules\GiftCard\Commands;

use App\Modules\GiftCard\GiftCard;
use Illuminate\Console\Command;

class MakeGiftCard extends Command
{
    protected $signature = 'yike:make-gift-card {name?} {--tokens_count=0} {--days=0}';

    protected $description = '创建礼品卡';

    public function handle()
    {
        $defaultPlan = config('quota.pricings.weekly');

        $name = $this->argument('name') ?? $defaultPlan['title'];
        $tokensCount = $this->option('tokens_count') ?: $defaultPlan['tokens_count'];
        $days = $this->option('days') ?: $defaultPlan['days'];

        $card = new GiftCard([
            'name' => $name,
            'tokens_count' => $tokensCount,
            'days' => $days,
        ]);

        $card->save();

        $this->info('创建成功：');

        $this->info('名称：'.$card->name);
        $this->info('编码：'.$card->code);
        $this->info('tokens：'.$card->tokens_count);
        $this->info('有效期：'.$card->expired_at);
        $this->info('有效天数：'.$card->days);
    }
}
