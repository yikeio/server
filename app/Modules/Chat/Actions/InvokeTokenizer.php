<?php

namespace App\Modules\Chat\Actions;

use App\Modules\Chat\Exceptions\InvokeTokenizerException;
use App\Modules\Common\Actions\Action;
use Illuminate\Support\Facades\Http;

class InvokeTokenizer extends Action
{
    public function handle(string $content, string $model): array
    {
        $response = Http::asJson()
            ->post(config('openai.tokenizer.endpoint'), [
                'model' => $model,
                'content' => $content,
            ]);

        if (!$response->successful() || !is_array($response->json('tokens'))) {
            throw new InvokeTokenizerException("[CHAT] 调用 OpenAI Tokenizer 服务失败：{$response->body()}");
        }

        return $response->json('tokens');
    }
}
