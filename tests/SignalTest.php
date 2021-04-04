<?php

namespace Spatie\SignalAwareCommand\Tests;

use Spatie\SignalAwareCommand\Signal;
use Spatie\SignalAwareCommand\Tests\TestClasses\TestCommand;

class SignalTest extends TestCase
{
    protected Signal $signal;

    protected bool $executed = false;

    public function setUp(): void
    {
        $this->signal = new Signal();
    }

    /** @test */
    public function it_can_register_and_execute_signal_handling_code()
    {
        $this->signal->handle(SIGINT, function() {
            $this->executed = true;
        });

        $this->signal->executeSignalHandlers(SIGINT, new TestCommand());

        $this->assertTrue($this->executed);
    }
}
