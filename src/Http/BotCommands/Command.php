<?php

namespace BlackRiver\TelegramBot\Http\BotCommands;

use Illuminate\Http\Request;
use BlackRiver\TelegramBot\Client;
use BlackRiver\TelegramBot\Contracts\Command as CommandContract;

class Command implements CommandContract
{
    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Telegram Client.
     *
     * @var \BlackRiver\TelegramBot\Client
     */
    protected $client;

    /**
     * Create a new Command instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \BlackRiver\TelegramBot\Client  $client
     * @return void
     */
    public function __construct(Request $request, Client $client)
    {
        $this->request = $request;

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message)
    {
        //
    }
}
