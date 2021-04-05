# Handle signals in artisan commands

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-signal-aware-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-signal-aware-command)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-signal-aware-command/run-tests?label=tests)](https://github.com/spatie/laravel-signal-aware-command/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-signal-aware-command/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-signal-aware-command/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-signal-aware-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-signal-aware-command)

Using this package you can easily handle signals like `SIGINT`,  `SIGTERM` in your Laravel app.

Here's a quick example where the `SIGINT` signal is handled.

```php
use Spatie\SignalAwareCommand\SignalAwareCommand;

class YourCommand extends SignalAwareCommand
{
    protected $signature = 'your-command';

    public function handle()
    {
        $this->info('Command started...');

        sleep(100);
    }

    public function onSigint()
    {
        // will be executed when you stop the command
    
        $this->info('You stopped the command!');
    }
}
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-signal-aware-command.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/package-laravel-signal-aware-command-laravel)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-signal-aware-command
```

## Usage

In order to make an Artisan command signal aware you need to let it extend `SignalAwareCommand`.

```php
use Spatie\SignalAwareCommand\SignalAwareCommand;

class YourCommand extends SignalAwareCommand
{
    // your code
}
```

### Handling signals

There are three ways to handle signals:
- on the command itself
- via the `Signal` facade
- using the `SignalReceived` event

#### On the command

To handle signals on the command itself, you need to let your command extend `SignalAwareCommand`. Next, define a method that starts with `on` followed by the name of the signal. Here's an example where the `SIGINT` signal is handled.

```php
use Spatie\SignalAwareCommand\SignalAwareCommand;

class YourCommand extends SignalAwareCommand
{
    protected $signature = 'your-command';

    public function handle()
    {
        $this->info('Command started...');

        sleep(100);
    }

    public function onSigint()
    {
        // will be executed when you stop the command
    
        $this->info('You stopped the command!');
    }
}
```

#### Via the `Signal` facade

Using the `Signal` facade you can register signal handling code anywhere in your app.

First, you need to define the signals you want to handle in your command in the `handlesSignals` property.

```php
use Spatie\SignalAwareCommand\SignalAwareCommand;

class YourCommand extends SignalAwareCommand
{
    protected $signature = 'your-command';
    
    protected $handlesSignals = [SIGINT];

    public function handle()
    {
        (new SomeOtherClass())->performSomeWork();

        sleep(100);
    }
}
```

In any class you'd like you can use the `Signal` facade to register code that should be executed when a signal is received.

```php
use Illuminate\Console\Command;
use Spatie\SignalAwareCommand\Facades\Signal;

class SomeOtherClass
{
    public function performSomeWork()
    {
        Signal::handle(SIGINT, function(Command $commandThatReceivedSignal) {
            $commandThatReceivedSignal->info('Received the SIGINT signal!');
        })
    }
}
```

You can call `clearHandlers` if you want to remove a handler that was previously registered.

```php
use Spatie\SignalAwareCommand\Facades\Signal;

public function performSomeWork()
{
    Signal::handle(SIGNINT, function() {
        // perform cleanup
    });
    
    $this->doSomeWork();
    
    // at this point doSomeWork was executed without any problems
    // running a cleanup isn't necessary anymore
    Signal::clearHandlers(SIGINT);
}
```

To clear all handlers for all signals use `Signal::clearHandlers()`.

#### Using the `SignalReceived` event


Whenever a signal is received, the `Spatie\SignalAwareCommand\Events\SignalReceived` event is fired.

To register which events you want to receive you must define a `handlesSignals` property on your command. Here's an example where we register listening for the `SIGINT` signal.

```php
use Spatie\SignalAwareCommand\SignalAwareCommand

class YourCommand extends SignalAwareCommand
{
    protected $signature = 'your-command';
    
    protected $handlesSignals = [SIGINT];

    public function handle()
    {
        (new SomeOtherClass())->performSomeWork();

        sleep(100);
    }
}
```

In any class you'd like you can listen for the `SignalReceived` event.

```php
use Spatie\SignalAwareCommand\Events\SignalReceived;
use Spatie\SignalAwareCommand\Signals;

class SomeOtherClass
{
    public function performSomeWork()
    {
        Event::listen(function(SignalReceived $event) {
            $signalNumber = $event->signal;
            
            $signalName = Signals::getSignalName($signalNumber);
        
            $event->command->info("Received the {$signalName} signal");
        });
    }
}
```

## Learn how this package was built

The foundations of this pacakge were coded up in [this live stream on YouTube](https://www.youtube.com/watch?v=D9hxQoD47jI).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
