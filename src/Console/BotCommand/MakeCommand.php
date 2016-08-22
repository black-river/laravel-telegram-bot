<?php

namespace BlackRiver\TelegramBot\Console\BotCommand;

use Illuminate\Console\GeneratorCommand;

class MakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:bot-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Bot command';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Command';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/command.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\BotCommands';
    }
}
