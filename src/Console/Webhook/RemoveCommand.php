<?php

namespace BlackRiver\TelegramBot\Console\Webhook;

use Illuminate\Console\Command;
use BlackRiver\TelegramBot\Client;

class RemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the webhook integration';

    /**
     * Telegram Client.
     *
     * @var \BlackRiver\TelegramBot\Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param  \BlackRiver\TelegramBot\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->client->send('setWebhook');

        return $this->info($result['description']);
    }
}
