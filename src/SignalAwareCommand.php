<?php

namespace Spatie\SignalAwareCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\SignalAwareCommand\Events\SignalReceived;
use Symfony\Component\Console\Command\SignalableCommandInterface;

abstract class SignalAwareCommand extends Command implements SignalableCommandInterface
{
    public function getSubscribedSignals(): array
    {
        return array_merge($this->autoDiscoverSignals(), $this->handlesSignals ?? []);
    }

    public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        event(new SignalReceived($signal, $this));

        $this
            ->executeRegisteredSignalHandlers($signal)
            ->handleSignalMethodOnCommandClass($signal);

        return $signal;
    }

    protected function executeRegisteredSignalHandlers(int $signal): self
    {
        app(Signal::class)->executeSignalHandlers($signal, $this);

        return $this;
    }

    protected function handleSignalMethodOnCommandClass(int $signal): self
    {
        if (! $signalName = Signals::getSignalName($signal)) {
            return $this;
        }

        $methodName = Str::camel("on {$signalName}");

        if (! method_exists($this, $methodName)) {
            return $this;
        }

        $this->$methodName($signal);

        return $this;
    }

    protected function autoDiscoverSignals(): array
    {
        return collect(get_class_methods($this))
            ->filter(fn (string $methodName) => Str::startsWith($methodName, 'on'))
            ->map(function (string $methodName) {
                $possibleSignalName = Str::of($methodName)->after('on')->upper();

                return Signals::getSignalForName($possibleSignalName);
            })
            ->filter()
            ->toArray();
    }
}
