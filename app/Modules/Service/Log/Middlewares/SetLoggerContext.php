<?php

namespace App\Modules\Service\Log\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetLoggerContext
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        Log::shareContext([
            'method' => $request->getMethod(),
            'uri' => $request->getPathInfo(),
            'query' => $request->query(),
            'body' => $request->json()->all(),
            'referer' => $request->header('referer', $request->header('Referer')),
            ...$this->flattenHeaders($request->header() ?? []),
            'client_ip' => $request->getClientIp(),
            'client_ips' => implode(',', $request->getClientIps() ?? []),
            'user_id' => $user?->id ?? 0,
            'client_id' => $request->client()?->id ?? 0,
            'client_name' => $request->client()?->name ?? '',
        ]);

        return $next($request);
    }

    private function flattenHeaders(array $headers): array
    {
        $flattenHeaders = [];

        foreach ($headers as $key => $value) {
            $flattenHeaders[Str::lower($key)] = is_array($value) ? implode(',', $value) : $value;
        }

        return $flattenHeaders;
    }
}
