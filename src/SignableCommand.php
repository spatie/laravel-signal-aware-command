<?php

namespace Spatie\SignableCommand;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;

abstract class SignableCommand extends Command implements SignalableCommandInterface
{
    public function getSubscribedSignals(): array
    {
        return $this->handlesSignals ?? [];
    }

    public function handleSignal(int $signal): void
    {
        app(Signal::class)->executeSignalHandlers($signal, $this);
    }
}
