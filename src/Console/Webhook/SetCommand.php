<?php

namespace BlackRiver\TelegramBot\Console\Webhook;

use Illuminate\Console\Command;
use BlackRiver\TelegramBot\Client;
use Illuminate\Contracts\Config\Repository;

class SetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:set
                            {path? : The webhook url path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the webhook url';

    /**
     * Config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Telegram Client.
     *
     * @var \BlackRiver\TelegramBot\Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \BlackRiver\TelegramBot\Client  $client
     * @return void
     */
    public function __construct(Repository $config, Client $client)
    {
        parent::__construct();

        $this->config = $config;

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parameters = [
            'url' => $this->getUrl(),
        ];

        if ($certificate = $this->config->get('telegram.certificate')) {
            $parameters['certificate'] = fopen($certificate, 'r');
        }

        $result = $this->client->send('setWebhook', $parameters);

        return $this->info($result['description'].': '.$parameters['url']);
    }

    /**
     * Get a secure url.
     *
     * @return string
     */
    protected function getUrl()
    {
        $url = $this->config->get('app.url', env('APP_URL'));

        $secureUrl = preg_replace('/^http:\/\//', 'https://', $url);

        $path = $this->argument('path') ?: $this->config->get('telegram.token');

        return rtrim($secureUrl, '/').'/'.ltrim($path, '/');
    }
}
