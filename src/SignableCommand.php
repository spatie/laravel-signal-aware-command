<?php

namespace Spatie\SignableCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\SignalableCommandInterface;

abstract class SignableCommand extends Command implements SignalableCommandInterface
{
    public function getSubscribedSignals(): array
    {
        return $this->handlesSignals ?? [];
    }

    public function handleSignal(int $signal): void
    {
        $this
            ->executeRegisteredSignalHandlers($signal)
            ->handleSignalMethodOnCommandClass($signal);
    }

    protected function executeRegisteredSignalHandlers(int $signal): self
    {
        app(Signal::class)->executeSignalHandlers($signal, $this);

        return $this;
    }

    protected function handleSignalMethodOnCommandClass(int $signal): self
    {
        if (! $signalName = $this->getSignalName($signal)) {
            return $this;
        }

        $methodName = Str::camel("on {$signalName}");

        if (! method_exists($this, $methodName)) {
            return $this;
        }

        $this->$methodName($signal);

        return $this;
    }

    protected function getSignalName(int $lookingForSignalNumber): ?string
    {
        foreach (get_defined_constants(true)['pcntl'] as $signalName => $signalNumber) {
            if ($signalNumber !== $lookingForSignalNumber) {
                continue;
            }

            if (Str::startsWith($signalName, 'SIG_')) {
                continue;
            }

            if (! Str::startsWith($signalName, 'SIG')) {
                continue;
            }

            return $signalName;
        }

        return null;
    }
}
