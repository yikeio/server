<?php

namespace App\Modules\Payment\Gateways\Payjs;

use App\Modules\Payment\Exceptions\GatewayException;
use App\Modules\Payment\Gateways\GatewayInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Throwable;

class Gateway implements GatewayInterface
{
    protected string $merchantId;

    protected string $secretKey;

    protected string $notifyUrl;

    protected string $endpoint;

    public function __construct(protected array $config = [])
    {
        $this->merchantId = $config['merchant_id'];
        $this->secretKey = $config['secret_key'];
        $this->notifyUrl = $config['notify_url'];
        $this->endpoint = $config['endpoint'];
    }

    public function native(array $parameters): array
    {
        return $this->post('/api/native', [
            ...$this->config['native']['default'] ?? [],
            ...$parameters,
            'mchid' => $this->merchantId,
            'notify_url' => $this->notifyUrl,
            'time_expire' => now()->addSeconds($this->getTtl())->format('YmdHis'),
        ]);
    }

    public function getTtl(): int
    {
        return $this->config['native']['ttl'] ?? 3600;
    }

    public function getName(): string
    {
        return \App\Modules\Payment\Enums\Gateway::PAYJS->value;
    }

    public function resolveNumber(array $parameters): string
    {
        return $parameters['payjs_order_id'] ?? '';
    }

    public function isPaid(string $number): bool
    {
        $response = $this->get('/api/check', [
            'payjs_order_id' => $number,
        ]);

        return Arr::get($response, 'status') === 1;
    }

    protected function post(string $endpoint, array $parameters): array
    {
        try {
            $response = Http::asForm()
                ->baseUrl($this->endpoint)
                ->post($endpoint, [
                    ...$parameters,
                    'sign' => $this->sign($parameters),
                ]);
        } catch (Throwable $e) {
            throw new GatewayException("[PAYJS] - 调用支付网关失败：{$e->getMessage()}");
        }

        $this->check($response);

        return $response->json();
    }

    protected function get(string $endpoint, array $parameters): array
    {
        try {
            $response = Http::baseUrl($this->endpoint)
                ->get($endpoint, [
                    ...$parameters,
                    'sign' => $this->sign($parameters),
                ]);
        } catch (Throwable $e) {
            throw new GatewayException("[PAYJS] - 调用支付网关失败：{$e->getMessage()}");
        }

        $this->check($response);

        return $response->json();
    }

    public function isValidSign(array $parameters): bool
    {
        $sign = $parameters['sign'] ?? '';

        unset($parameters['sign']);

        return $sign === $this->sign($parameters);
    }

    protected function sign(array $parameters): string
    {
        ksort($parameters);

        return strtoupper(md5(urldecode(http_build_query($parameters)).'&key='.$this->secretKey));
    }

    protected function check(Response $response): void
    {
        if (! $response->successful()) {
            throw new GatewayException("[PAYJS] - 调用支付网关失败：{$response->body()}");
        }

        if (Arr::get($response->json(), 'return_code') !== 1) {
            throw new GatewayException("[PAYJS] - 调用支付网关失败：{$response['return_msg']}");
        }
    }

    public function resolveContext(array $parameters): array
    {
        return Arr::only($parameters, ['code_url', 'qrcode']);
    }
}
