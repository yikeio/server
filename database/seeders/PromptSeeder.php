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
            Prompt::updateOrCreate([
                'name' => $prompt['title'],
            ], [
                'description' => $prompt['remark'],
                'prompt_cn' => $prompt['desc_cn'],
                'prompt_en' => $prompt['desc_en'],
                'sort_order' => $prompt['weight'] ?? 0,
            ]);
        }
    }
}
