<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | Each bot is given a unique authentication token when it is created.
    |
    */

    'token' => env('TELEGRAM_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Public Key Certificate Path
    |--------------------------------------------------------------------------
    |
    | Whenever there is an update for the bot, Telegram will send an HTTPS POST
    | request to the specified url. To use a self-signed certificate, you need
    | to specify the path of your public key certificate.
    |
    */

    'certificate' => env('TELEGRAM_CERTIFICATE'),

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Commands
    |--------------------------------------------------------------------------
    |
    | Make your Bot to do awesome things!
    |
    */

    'commands' => [
        '/ping' => BlackRiver\TelegramBot\Http\BotCommands\PingCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Client Options
    |--------------------------------------------------------------------------
    |
    | You can specify the handler or default request options.
    |
    */

    'client_options' => [
        //
    ],

];
