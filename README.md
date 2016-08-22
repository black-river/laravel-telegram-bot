Laravel Telegram Bot
====================

![cover](https://cloud.githubusercontent.com/assets/5261079/17837503/ac398cd0-67bd-11e6-8493-962ef6111d58.png)

## Installation

Require this package, with [Composer](https://getcomposer.org/):

```bash
composer require black-river/telegram-bot
```

Add the service provider to the `providers` array of your `config/app.php`:

```php
BlackRiver\TelegramBot\ServiceProvider::class,
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="BlackRiver\TelegramBot\ServiceProvider"
```

Set environment variables in your `.env`:

```
APP_URL="http://your-bot.com"
...
TELEGRAM_TOKEN="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11"
```

To use a [self-signed certificate](https://core.telegram.org/bots/self-signed), you should also specify the certificate path:

```
TELEGRAM_CERTIFICATE="/etc/nginx/ssl/your-bot.com.crt"
```

## Quickstart

Define the default webhook route in your route file:

```php
Route::post(config('telegram.token'), function (BlackRiver\TelegramBot\Bot $bot) {
    $bot->listen();
});
```

- Use the Bot's `listen` method to handle commands.

Set up the webhook url:

```bash
php artisan webhook:set
```

To ensure the bot is ready, send the `/ping` message:

![ping-command](https://cloud.githubusercontent.com/assets/5261079/17837506/b808b3ba-67bd-11e6-911d-42d1568ab068.png)

> To make sure there is no middleware or prefix that could "block" the default webhook route, check your `app/Providers/RouteServiceProvider.php`.

## Webhook URL

You can change the default webhook route to your own:

```php
Route::post('your-secret-path', function (BlackRiver\TelegramBot\Bot $bot) {
    $bot->listen();
});
```

```bash
php artisan webhook:set your-secret-path
```

To remove the webhook integration, run `php artisan webhook:remove`.

## Bot Commands

Create a new Bot Command in the `app/Http/BotCommands` directory:

```bash
php artisan make:bot-command NameCommand
```

Edit the `handle` method of `app/Http/BotCommands/NameCommand.php`:

```php
$this->client->send('sendMessage', [
    'chat_id' => $this->request->json('message.chat.id'),
    'text' => 'Hello, '.trim($message),
]);
```

- Use the Client's `send` method to call any of the [available methods](https://core.telegram.org/bots/api#available-methods).

- Use the Client's `save` method to save Telegram files.

- The Client and Request are available within a Command via `$this->client` and `$this->request` respectively.

Add the new command to the `commands` array of your `config/telegram.php`:

```php
'/name' => App\Http\BotCommands\NameCommand::class,
```

Send the `/name Johnny` message:

![name-command](https://cloud.githubusercontent.com/assets/5261079/17837505/b5d147c4-67bd-11e6-8aa3-b65a59151815.png)

## Raw Webhook

```php
Route::post('your-secret-path', function (Illuminate\Http\Request $request, BlackRiver\TelegramBot\Client $client) {
    // $bot->listen();

    $update = $request->json()->all();

    $client->send('sendMessage', [
        'chat_id' => $request->json('message.chat.id'),
        'text' => 'I\'ve got it!',
    ]);
});
```

## Facades

Add facades to the `aliases` array of your `config/app.php`:

```php
'TelegramBot' => BlackRiver\TelegramBot\Facades\TelegramBot::class,
'TelegramApi' => BlackRiver\TelegramBot\Facades\TelegramApi::class,
```

Usage:

```php
Route::post('your-secret-path', function () {
    // TelegramBot::listen();

    TelegramApi::send('sendMessage', [
        'chat_id' => Request::json('message.chat.id'),
        'text' => 'Hey!',
    ]);
});
```

## Examples

Send a photo to your chat:

```php
Route::post('photo', function (BlackRiver\TelegramBot\Client $client) {
    $client->send('sendPhoto', [
        'chat_id' => 'your-chat-id',
        'photo' => fopen(storage_path('photo.png'), 'r'),
    ]);
});
```

Save incoming files:

```php
Route::post('your-secret-path', function (Illuminate\Http\Request $request, BlackRiver\TelegramBot\Client $client) {
    $doc = $request->json('message.document');

    $filename = $doc['file_name'];

    $file = $client->send('getFile', ['file_id' => $doc['file_id']]);

    $client->save($file['result']['file_path'], storage_path($filename));
});
```

## Extending

To extend the Client, add a new macro to the `boot` method of your `app/Providers/AppServiceProvider.php`:

```php
app('BlackRiver\TelegramBot\Client')->macro('sendUploadedPhoto',
    function ($chatId, \Illuminate\Http\UploadedFile $photo) {
        $saved = $photo->move(storage_path(), $photo->getClientOriginalName());

        $this->send('sendPhoto', [
            'chat_id' => $chatId,
            'photo' => fopen($saved->getRealPath(), 'r'),
        ]);
    }
);
```

Send an uploaded photo to your chat:

```php
Route::post('upload', function (Illuminate\Http\Request $request, BlackRiver\TelegramBot\Client $client) {
    $client->sendUploadedPhoto('your-chat-id', $request->file('photo'));
});
```

## Handle errors

The Client uses [Guzzle Http Client](http://docs.guzzlephp.org/en/latest/) to interact with Telegram API, so you can handle [Guzzle Exceptions](http://docs.guzzlephp.org/en/latest/quickstart.html#exceptions):

```php
try {
    $client->send('methodName', []);
} catch (GuzzleHttp\Exception\ClientException $e) {
    // 400 level errors...
} catch (GuzzleHttp\Exception\ServerException $e) {
    // 500 level errors...
} catch (GuzzleHttp\Exception\RequestException $e) {
    // Connection timeout, DNS errors, etc.
}
```

## Lumen

Require this package, with [Composer](https://getcomposer.org/):

```bash
composer require black-river/telegram-bot
```

Add the service provider to the `Register Service Providers` section of your `bootstrap/app.php`:

```php
$app->register(BlackRiver\TelegramBot\ServiceProvider::class);
```

Set the `APP_URL`, `TELEGRAM_TOKEN` and `TELEGRAM_CERTIFICATE` variables in your `.env`.

Copy the vendor's `telegram.php` config file to your `config` directory:

```bash
mkdir config/ && cp vendor/black-river/telegram-bot/config/telegram.php config/
```

Define the default webhook route in your route file:

```php
$app->post(config('telegram.token'), function (BlackRiver\TelegramBot\Bot $bot) {
    $bot->listen();
});
```

## License

Laravel Telegram Bot is licensed under [The MIT License (MIT)](LICENSE).
