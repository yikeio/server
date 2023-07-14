<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Chat\Conversation;
use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Message;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GetStats extends Endpoint
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return Cache::remember('user_stats:'.$user->id, 5, function () use ($user) {
            return [
                // 总邀请人数
                'invitations' => User::withTrashed()->where('referrer_id', $user->id)->count(),

                // 消息
                'messages' => [
                    'total' => Message::withTrashed()->where('role', MessageRole::USER)->whereBelongsTo($user, 'creator')->count(),
                    'today_total' => Message::withTrashed()->where('role', MessageRole::USER)->whereBelongsTo($user, 'creator')->whereDate('created_at', today())->count(),
                    // 截止上个月的总数
                    'last_today_total' => Message::withTrashed()->where('role', MessageRole::USER)->whereBelongsTo($user, 'creator')->where('created_at', '<=', today()->subMonth())->count(),
                    // 近一个月的用户数
                    'recent_30days_total' => Message::withTrashed()->where('role', MessageRole::USER)->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->count(),
                    // 30 天每日新增数
                    'recent_daily_count' => $this->padInDays(Message::withTrashed()->where('role', MessageRole::USER)->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($messages) {
                        return $messages->created_at->format('Y-m-d');
                    })->map(function ($messages) {
                        return ['messages_count' => $messages->count(), 'tokens_count' => $messages->sum('tokens_count')];
                    }), 30, function ($date, $collection) {
                        return $collection->get($date, ['messages_count' => 0, 'tokens_count' => 0]);
                    }),
                ],
                // 会话
                'conversations' => [
                    'total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->count(),
                    'today_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->whereDate('created_at', today())->count(),
                    // 截止上个月的总数
                    'last_today_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '<=', today()->subMonth())->count(),
                    // 近一个月的总数
                    'recent_30days_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->count(),
                    // 30 天每日新增数
                    'recent_daily_count' => $this->padInDays(Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($conversations) {
                        return $conversations->created_at->format('Y-m-d');
                    })->map(function ($conversations) {
                        return $conversations->count();
                    })),
                ],
                // 会话使用的 token
                'tokens' => [
                    'total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->sum('tokens_count'),
                    'today_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->whereDate('created_at', today())->sum('tokens_count'),
                    // 截止上个月的总数
                    'last_today_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '<=', today()->subMonth())->sum('tokens_count'),
                    // 近一个月的token数
                    'recent_30days_total' => Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->sum('tokens_count'),
                    // 30 天每日新增数
                    'recent_daily_count' => $this->padInDays(Conversation::withTrashed()->whereBelongsTo($user, 'creator')->where('created_at', '>=', today()->subMonth())->get()->groupBy(function ($conversations) {
                        return $conversations->created_at->format('Y-m-d');
                    })->map(function ($conversations) {
                        return $conversations->sum('tokens_count');
                    })),
                ],
            ];
        });
    }

    public function padInDays(Collection $collection, int $days = 30, callable $callback = null)
    {
        $dates = array_map(function ($date) {
            return today()->subDays($date)->format('Y-m-d');
        }, range(0, $days));

        $callback = $callback ?: function ($date, $collection) {
            return ['value' => $collection->get($date, 0)];
        };

        return collect($dates)->map(function ($date) use ($collection, $callback) {
            return [
                'date' => $date,
                ...$callback($date, $collection),
            ];
        })->sortBy('date')->values();
    }
}
