<?php

namespace BlackRiver\TelegramBot;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use BlackRiver\TelegramBot\Client;
use Illuminate\Contracts\Config\Repository;

class Bot
{
    /**
     * Config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

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
     * Type of the command entity.
     *
     * @var string
     */
    const COMMAND_ENTITY = 'bot_command';

    /**
     * Create a new Telegram Bot instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Http\Request  $request
     * @param  \BlackRiver\TelegramBot\Client  $client
     * @return void
     */
    public function __construct(Repository $config, Request $request, Client $client)
    {
        $this->config = $config;

        $this->request = $request;

        $this->client = $client;
    }

    /**
     * Listen commands.
     *
     * @return mixed|null
     */
    public function listen()
    {
        if ($entity = $this->getCommandEntity()) {
            list($name, $message) = $this->getEntityText($entity);

            if ($command = $this->getCommand($name)) {
                return (new $command($this->request, $this->client))->handle($message);
            }
        }
    }

    /**
     * Get the command entity.
     *
     * @return array|null
     */
    protected function getCommandEntity()
    {
        $entity = $this->request->json('message.entities.0');

        if (Arr::get($entity, 'type') === self::COMMAND_ENTITY) {
            return $entity;
        }
    }

    /**
     * Get the text of a given entity.
     *
     * @param  array  $entity
     * @return array
     */
    protected function getEntityText(array $entity)
    {
        $text = $this->request->json('message.text');

        return [
            Str::substr($text, $entity['offset'], $entity['length']),
            Str::substr($text, $entity['offset'] + $entity['length']),
        ];
    }

    /**
     * Get the command class for a given name.
     *
     * @param  string  $name
     * @return string|null
     */
    protected function getCommand($name)
    {
        $name = Str::lower($name);

        $commands = $this->config->get('telegram.commands');

        return Arr::get($commands, $name, Arr::get($commands, ltrim($name, '/')));
    }
}
