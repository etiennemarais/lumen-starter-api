<?php

return [
    'endpoint' => env('SLACK_WEBHOOK_URL'),
    'channel' => env('SLACK_ERROR_CHANNEL'),
    'username' => env('SLACK_BOT_NAME', 'starter-bot'),
    'icon' => env('SLACK_BOT_EMOJI' ,':mushroom:'),
    'link_names' => false,
    'unfurl_links' => false,
    'unfurl_media' => true,
    'allow_markdown' => true,
    'markdown_in_attachments' => ['pretext', 'text', 'title', 'fields', 'fallback']
];
