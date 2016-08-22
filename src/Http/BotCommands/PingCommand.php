<?php

namespace BlackRiver\TelegramBot\Http\BotCommands;

use BlackRiver\TelegramBot\Http\BotCommands\Command;

class PingCommand extends Command
{
    /**
     * Handle the command.
     *
     * @param  string  $message
     * @return void
     */
    public function handle($message)
    {
        $this->client->send('sendMessage', [
            'chat_id' => $this->request->json('message.chat.id'),
            'text' => 'pong',
        ]);
    }
}
