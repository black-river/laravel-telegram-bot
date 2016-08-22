<?php

namespace BlackRiver\TelegramBot\Facades;

use BlackRiver\TelegramBot\Bot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BlackRiver\TelegramBot\Bot
 */
class TelegramBot extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Bot::class;
    }
}
