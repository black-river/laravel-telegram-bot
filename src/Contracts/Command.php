<?php

namespace BlackRiver\TelegramBot\Contracts;

interface Command
{
    /**
     * Handle the command.
     *
     * @param  string  $message
     * @return void
     */
    public function handle($message);
}
