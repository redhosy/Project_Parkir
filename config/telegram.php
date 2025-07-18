<?php

use Telegram\Bot\Commands\HelpCommand; // Pastikan ini ada jika Anda ingin menggunakan HelpCommand

return [
    /*
    |--------------------------------------------------------------------------
    | Your Telegram Bots
    |--------------------------------------------------------------------------
    | You may specify multiple bots for your app.
    | Each bot has a token and a username.
    */
    'bots' => [
        'mybot' => [ // Nama bot default yang Anda gunakan
            'token' => env('TELEGRAM_BOT_TOKEN'), // Pastikan ini mengambil dari .env
            'username' => env('TELEGRAM_BOT_USERNAME', null), // Opsional, bisa null
            'commands' => [
                // \Telegram\Bot\Commands\HelpCommand::class, // Hapus komentar jika ingin menggunakan perintah bot
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Bot Name
    |--------------------------------------------------------------------------
    | This name is used to identify the default bot.
    | You can use it to send messages without specifying the bot name.
    */
    'default_bot' => 'mybot', // Pastikan ini sesuai dengan nama bot di atas

    /*
    |--------------------------------------------------------------------------
    | Telegram API URL
    |--------------------------------------------------------------------------
    | If you have a self-hosted Telegram Bot API server, you can specify its URL here.
    | Otherwise, it will use the default Telegram API URL.
    */
    // Ini adalah URL default yang digunakan oleh SDK.
    // Jika Anda tidak memiliki server API bot Telegram sendiri, biarkan ini.
    'api_url' => 'https://api.telegram.org/', // Baris ini tidak perlu jika menggunakan default

    /*
    |--------------------------------------------------------------------------
    | Asynchronous Requests
    |--------------------------------------------------------------------------
    | If you want to use Guzzle's async requests, set this to true.
    */
    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Handler
    |--------------------------------------------------------------------------
    | If you want to use a specific HTTP client handler, specify its class name here.
    | Otherwise, it will use Guzzle's default handler.
    */
    'http_client_handler' => null,

    /*
    |--------------------------------------------------------------------------
    | Http Client Options
    |--------------------------------------------------------------------------
    | If you want to specify additional Guzzle client options, specify them here.
    | For example, to enable debugging, you can set 'debug' => true.
    */
    'http_client_options' => [
        'debug' => true, // <--- BIARKAN INI TRUE UNTUK DEBUGGING
        // 'proxy' => 'http://proxy.example.com:8080',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resolve Commands
    |--------------------------------------------------------------------------
    | If you want to resolve commands through the Laravel service container, set this to true.
    */
    'resolve_commands' => false, // Set ke false jika Anda tidak menggunakan sistem perintah bot

    /*
    |--------------------------------------------------------------------------
    | Register Telegram Global Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use the SDK's built in command handler system,
    | You can register all the global commands here.
    |
    */
    'commands' => [
        // HelpCommand::class, // Hapus komentar jika Anda ingin menggunakan perintah /help
    ],

    // Bagian command_groups dan shared_commands tidak perlu jika Anda tidak menggunakannya.
    // Saya menghapusnya untuk mempersingkat dan fokus pada konfigurasi dasar.
];
