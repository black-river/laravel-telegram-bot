<?php

namespace BlackRiver\TelegramBot\Facades;

use BlackRiver\TelegramBot\Client;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BlackRiver\TelegramBot\Client
 */
class TelegramApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
