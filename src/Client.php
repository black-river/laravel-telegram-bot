<?php

namespace BlackRiver\TelegramBot;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Config\Repository;

class Client
{
    use Macroable;

    /**
     * Config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Guzzle Http Client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzle;

    /**
     * Telegram API Base Uri.
     *
     * @var string
     */
    const BASE_URI = 'https://api.telegram.org';

    /**
     * Create a new Client instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;

        $options = $this->config->get('telegram.client_options');

        $this->guzzle = new Guzzle(
            array_merge($options, ['base_uri' => self::BASE_URI])
        );
    }

    /**
     * Send a request to the Telegram Bot API.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @param  array   $options
     * @return array
     */
    public function send($method, array $parameters = [], array $options = [])
    {
        $method = ltrim($method, '/');

        $uri = '/bot'.$this->config->get('telegram.token').'/'.$method;

        $response = $this->guzzle->request('POST', $uri,
            array_merge($options, $this->getRequestOption($parameters))
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Save a file from Telegram.
     *
     * @param  string  $path
     * @param  string  $target
     * @param  array   $options
     * @return void
     */
    public function save($path, $target, array $options = [])
    {
        $uri = '/file/bot'.$this->config->get('telegram.token').'/'.$path;

        $this->guzzle->request('GET', $uri,
            array_merge($options, ['sink' => $target])
        );
    }

    /**
     * Get the request option.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function getRequestOption(array $parameters)
    {
        if (array_filter($parameters, 'is_resource')) {
            $data = [];

            foreach ($parameters as $name => $contents) {
                $data[] = compact('name', 'contents');
            }

            return ['multipart' => $data];
        }

        return ['form_params' => $parameters];
    }
}
