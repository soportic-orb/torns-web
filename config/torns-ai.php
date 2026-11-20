<?php

/*
|--------------------------------------------------------------------------
| AI writing in the blog editor (App\Ai\BlogAi)
|--------------------------------------------------------------------------
| Defaults only: the admin page (Settings → AI for the blog) stores its own
| values, with the keys encrypted, and those take precedence.
*/

return [
    'enabled' => env('TORNS_AI_ENABLED', '0'),
    'default_text' => env('TORNS_AI_DEFAULT_TEXT', 'claude'),
    'anthropic_key' => env('TORNS_ANTHROPIC_API_KEY'),
    'claude_model' => env('TORNS_CLAUDE_MODEL'),
    'openai_key' => env('TORNS_OPENAI_API_KEY'),
    'openai_text_model' => env('TORNS_OPENAI_TEXT_MODEL'),
    'openai_image_model' => env('TORNS_OPENAI_IMAGE_MODEL'),
];
