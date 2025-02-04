<?php

namespace Spatie\SignalAwareCommand;

use Illuminate\Console\Command;

class Signal
{
    protected array $registeredSignalHandlers = [];

    public function handle(int $signal, callable $callable): self
    {
        $this->registeredSignalHandlers[$signal][] = $callable;

        return $this;
    }

    public function executeSignalHandlers(int $signal, Command $command): self
    {
        foreach ($this->registeredSignalHandlers[$signal] ?? [] as $signalHandler) {
            $signalHandler($command);
        }

        return $this;
    }

    public function clearHandlers(?int $signal = null): self
    {
        if (is_null($signal)) {
            $this->registeredSignalHandlers = [];

            return $this;
        }

        $this->registeredSignalHandlers[$signal] = [];

        return $this;
    }
}
