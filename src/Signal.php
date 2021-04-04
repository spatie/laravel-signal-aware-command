<?php

namespace Spatie\SignableCommand;

use Illuminate\Console\Command;

class Signal
{
    protected $registeredSignalHandlers = [];

    public function handle(int $signal, callable $callable)
    {
        $this->registeredSignalHandlers[$signal][] = $callable;
    }

    public function executeSignalHandlers(int $signal, Command $command)
    {
        foreach ($this->registeredSignalHandlers[$signal] ?? [] as $signalHandler) {
            $signalHandler($signal, $command);
        }
    }
}
