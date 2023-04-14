<?php

namespace App\Modules\Service\OpenAI;

use Illuminate\Support\Facades\Http;

class Tokenizer
{
    protected string $endpoint;

    protected string $model;

    public function __construct(protected array $config)
    {
        $this->endpoint = $this->config['endpoint'];
    }

    protected function format(array $prompts): string
    {
        if (empty($prompts)) {
            return '';
        }

        $outputs = [];

        foreach ($prompts as $prompt) {
            $outputs[] = sprintf('%s:%s', $prompt['role'], $prompt['content']);
        }

        return implode("\n\n", $outputs);
    }

    public function tokenize(string|array $prompts): array
    {
        if (is_string($prompts)) {
            $content = $prompts;
        } else {
            $content = $this->format($prompts);
        }

        $response = Http::asJson()
            ->post($this->endpoint, [
                'model' => $this->model,
                'content' => $content,
            ]);

        if (! $response->successful() || ! is_array($response->json('tokens'))) {
            throw new TokenizerException("[CHAT] 调用 OpenAI Tokenizer 服务失败：{$response->body()}");
        }

        return $response->json('tokens');
    }

    public function predict(string|array $prompts): int
    {
        return count($this->tokenize($prompts));
    }

    public function predictUsage(array $prompts, string $completion): array
    {
        $promptTokens = $this->predict($prompts) + $this->getPromptStartingPrice();
        $completionTokens = $this->predict($completion);

        return [
            'prompt_tokens_count' => $promptTokens,
            'completion_tokens_count' => $completionTokens,
            'tokens_count' => $promptTokens + $completionTokens,
        ];
    }

    protected function getPromptStartingPrice(): int
    {
        return 8;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }
}
