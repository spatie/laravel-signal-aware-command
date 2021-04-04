<?php

namespace Spatie\SignableCommand;

class Signal
{
    protected $registeredSignalHandlers = [];

    public function handle(int $signal, callable $callable)
    {
        $this->registeredSignalHandlers[$signal][] = $callable;
    }

    public function executeSignalHandlers(int $signal)
    {
        foreach ($this->registeredSignalHandlers[$signal] ?? [] as $signalHandler) {
            $signalHandler($signal);
        }
    }
}
