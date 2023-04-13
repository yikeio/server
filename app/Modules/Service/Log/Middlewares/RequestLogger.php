<?php

namespace App\Modules\Service\Log\Middlewares;

use App\Modules\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = Str::uuid()->toString();

        Log::shareContext(['request_id' => $requestId]);

        return tap($next($request), fn ($response) => $response->headers->set('X-Request-Id', $requestId));
    }

    public function terminate(Request $request, Response $response): void
    {
        /** @var User $user */
        $user = $request->user();

        $items = [
            'method' => $request->getMethod(),
            'uri' => $request->getPathInfo(),
            'query' => $request->query(),
            'body' => $request->json()->all(),
            'referer' => $request->header('referer', $request->header('Referer')),
            'client_ip' => $request->getClientIp(),
            'client_ips' => implode(',', $request->getClientIps() ?? []),
            'user_id' => $user?->id ?? 0,
            'user_name' => $user?->username ?? '',
            'client_id' => $request->client()?->id ?? 0,
            'client_name' => $request->client()?->name ?? '',
        ];

        $contexts = [];

        foreach ($items as $key => $value) {
            $contexts["request_$key"] = $value;
        }

        Log::channel('request')->info('request logger middleware', [
            ...$contexts,
            ...$this->flattenHeaders($request->header() ?? []),
            'response_status_code' => $response->getStatusCode(),
            'response_content' => $response->isSuccessful() ? [] : json_decode($response->getContent(), true),
        ]);
    }

    private function flattenHeaders(array $headers): array
    {
        $flattenHeaders = [];

        foreach ($headers as $key => $value) {
            $flattenHeaders['header_'.Str::lower($key)] = is_array($value) ? implode(',', $value) : $value;
        }

        return Arr::except($flattenHeaders, ['authorization']);
    }
}
