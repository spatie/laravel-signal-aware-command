<?php

use Illuminate\Support\Facades\Event;
use Spatie\SignalAwareCommand\Events\SignalReceived;
use Spatie\SignalAwareCommand\Signal;
use Spatie\SignalAwareCommand\Tests\TestClasses\TestCommand;

beforeEach(function () {
    $this->signal = new Signal();
    $this->executed = false;

    Event::fake();
});

it('can register an execute signal handling code', function () {
    $this->signal->handle(SIGINT, function () {
        $this->executed = true;
    });

    $this->signal->executeSignalHandlers(SIGINT, new TestCommand());

    expect($this->executed)->toBeTrue();
});

it('will fire the signal aware event', function () {
    /** @var \Spatie\SignalAwareCommand\SignalAwareCommand $command */
    $command = app()->make(TestCommand::class);

    $result = $command->handleSignal(SIGINT);

    Event::assertDispatched(SignalReceived::class);

    expect($result)->toEqual(SIGINT);
});

it('can clear registered handlers', function () {
    $this->signal->handle(SIGINT, function () {
        $this->executed = true;
    });

    $this->signal->clearHandlers(SIGINT);

    $this->signal->executeSignalHandlers(SIGINT, new TestCommand());

    expect($this->executed)->toBeFalse();
});
