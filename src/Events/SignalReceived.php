<?php

namespace Spatie\SignableCommand\Events;

use Illuminate\Console\Command;

class SignalReceived
{
    public function __construct(
        public int $signal,
        public Command $class
    ) {}
}
