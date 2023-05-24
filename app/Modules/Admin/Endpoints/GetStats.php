<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Message;
use App\Modules\Payment\Payment;
use App\Modules\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GetStats
{
    public function __invoke()
    {
        return Cache::remember('stats', app()->isLocal() ? 0 : 3600 * 5, function () {
            return [
                'users' => [
                    'total' => User::count(),

                    'today_total' => User::whereDate('created_at', today())->count(),
                    // 截止上个月的总用户数
                    'last_month_total' => User::where('created_at', '<=', today()->subMonth())->count(),

                    // 近一个月的用户数
                    'this_month_total' => User::where('created_at', '>=', today()->subMonth())->count(),

                    'leaderboards' => [
                        // 按照用户的会话数量进行排行
                        'by_conversation_count' => Conversation::groupBy('creator_id')->selectRaw('count(*) as count, creator_id')->orderBy('count', 'desc')->limit(10)->get()->map(function ($conversation) {
                            return [
                                'user' => $conversation->creator->only(['id', 'name', 'avatar']),
                                'count' => $conversation->count,
                            ];
                        }),
                        // 按照用户的付费金额进行排行
                        'by_payment_total' => User::where('paid_total', '>', 0)->orderBy('paid_total', 'desc')->limit(10)->get()->map(function ($user) {
                            return [
                                'user' => $user->only(['id', 'name', 'avatar']),
                                'paid_total' => $user->paid_total,
                            ];
                        }),
                        // 按照用户的邀请人数进行排行
                        'by_invitation_count' => User::where('referrals_count', '>', 0)->orderBy('referrals_count', 'desc')->limit(10)->get()->map(function ($user) {
                            return [
                                'user' => $user->only(['id', 'name', 'avatar']),
                                'referrals_count' => $user->referrals_count,
                            ];
                        }),
                    ],
                    // 过去 30 天每日新增用户数，并格式化成 [['date' => '2020-01-01', 'count' => 1], 并且按照日期升序排序，没有值的日期用 0 填充
                    'recent_daily_count' => $this->padInDays(User::where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($user) {
                        return $user->created_at->format('Y-m-d');
                    })->map(function ($users) {
                        return $users->count();
                    })),
                ],
                'payments' => [
                    'total' => Payment::where('state', 'paid')->sum('amount'),
                    'today_total' => Payment::where('state', 'paid')->whereDate('created_at', today())->sum('amount'),
                    'last_month_total' => Payment::where('state', 'paid')->where('created_at', '<=', today()->subMonth())->sum('amount'),
                    'this_month_total' => Payment::where('state', 'paid')->where('created_at', '>=', today()->subMonth())->sum('amount'),

                    // 30 天每日新增付费金额
                    'recent_daily_amount' => $this->padInDays(Payment::where('state', 'paid')->where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($payment) {
                        return $payment->created_at->format('Y-m-d');
                    })->map(function ($payments) {
                        return $payments->sum('amount');
                    })),
                ],

                'conversations' => [
                    'total' => Conversation::count(),
                    'today_total' => Conversation::whereDate('created_at', today())->count(),
                    'last_month_total' => Conversation::where('created_at', '<=', today()->subMonth())->count(),
                    'this_month_total' => Conversation::where('created_at', '>=', today()->subMonth())->count(),

                    // 30 天每日新增会话数
                    'recent_daily_count' => $this->padInDays(Conversation::where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($conversation) {
                        return $conversation->created_at->format('Y-m-d');
                    })->map(function ($conversations) {
                        return $conversations->count();
                    })),
                ],

                'messages' => [
                    'total' => Message::count(),
                    'today_total' => Message::whereDate('created_at', today())->count(),
                    'last_month_total' => Message::where('created_at', '<=', today()->subMonth())->count(),
                    'this_month_total' => Message::where('created_at', '>=', today()->subMonth())->count(),

                    // 30 天每日新增消息数
                    'recent_daily_count' => $this->padInDays(Message::where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($messages) {
                        return $messages->created_at->format('Y-m-d');
                    })->map(function ($conversations) {
                        return $conversations->count();
                    })),
                ]
            ];
        });
    }

    public function padInDays(Collection $collection, int $days = 30, $valueKey = 'value')
    {
        $dates = array_map(function ($date) {
            return today()->subDays($date)->format('Y-m-d');
        }, range(0, $days));

        return collect($dates)->map(function ($date) use ($collection, $valueKey) {
            return [
                'date' => $date,
                $valueKey => $collection->get($date, 0),
            ];
        })->sortBy('date')->values();
    }
}
