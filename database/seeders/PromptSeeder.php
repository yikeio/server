<?php

namespace Database\Seeders;

use App\Modules\Prompt\Prompt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class PromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prompts = Http::timeout(10)->get('https://raw.githubusercontent.com/rockbenben/ChatGPT-Shortcut/main/src/data/prompt.json')
            ->json();

        foreach ($prompts as $prompt) {
            $promptZh = $prompt['zh'];

            Prompt::updateOrCreate([
                'name' => $promptZh['title'],
            ], [
                'description' => $promptZh['remark'],
                'prompt_cn' => $promptZh['description'],
                'prompt_en' => $promptZh['prompt'],
                'sort_order' => $promptZh['weight'] ?? 0,
                'greeting' => '嗨，欢迎来到一刻，你想聊点什么？',
            ]);
        }
    }
}
