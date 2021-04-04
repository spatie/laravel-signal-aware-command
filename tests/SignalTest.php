<?php

namespace Spatie\SignalAwareCommand\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\SignalAwareCommand\Events\SignalReceived;
use Spatie\SignalAwareCommand\Signal;
use Spatie\SignalAwareCommand\Tests\TestClasses\TestCommand;

class SignalTest extends TestCase
{
    protected Signal $signal;

    protected bool $executed = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->signal = new Signal();

        Event::fake();
    }

    /** @test */
    public function it_can_register_and_execute_signal_handling_code()
    {
        $this->signal->handle(SIGINT, function () {
            $this->executed = true;
        });

        $this->signal->executeSignalHandlers(SIGINT, new TestCommand());

        $this->assertTrue($this->executed);
    }

    /** @test */
    public function it_will_fire_the_signal_aware_event()
    {
        /** @var \Spatie\SignalAwareCommand\SignalAwareCommand $command */
        $command = app()->make(TestCommand::class);

        $command->handleSignal(SIGINT);

        Event::assertDispatched(SignalReceived::class);
    }
}
