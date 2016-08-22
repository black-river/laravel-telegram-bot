<?php

namespace BlackRiver\TelegramBot;

use BlackRiver\TelegramBot\Bot;
use BlackRiver\TelegramBot\Client;
use BlackRiver\TelegramBot\Console\Webhook\SetCommand;
use BlackRiver\TelegramBot\Console\Webhook\RemoveCommand;
use BlackRiver\TelegramBot\Console\BotCommand\MakeCommand;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the service.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/telegram.php' => config_path('telegram.php'),
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('telegram');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/telegram.php', 'telegram');

        $this->registerBot();

        $this->registerClient();

        $this->registerCommands();
    }

    /**
     * Register the bot.
     *
     * @return void
     */
    protected function registerBot()
    {
        $this->app->singleton(Bot::class, function ($app) {
            return new Bot($app['config'], $app['request'], $app[Client::class]);
        });

        $this->app->alias(Bot::class, 'telegram.bot');
    }

    /**
     * Register the client.
     *
     * @return void
     */
    protected function registerClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return new Client($app['config']);
        });

        $this->app->alias(Client::class, 'telegram.client');
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.webhook.set', function ($app) {
            return new SetCommand($app['config'], $app[Client::class]);
        });

        $this->app->singleton('command.webhook.remove', function ($app) {
            return new RemoveCommand($app[Client::class]);
        });

        $this->app->singleton('command.bot-command.make', function ($app) {
            return new MakeCommand($app['files']);
        });

        $this->commands(
            'command.webhook.set', 'command.webhook.remove',
            'command.bot-command.make'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Bot::class, 'telegram.bot',
            Client::class, 'telegram.client',
            'command.webhook.set', 'command.webhook.remove',
            'command.bot-command.make',
        ];
    }
}
