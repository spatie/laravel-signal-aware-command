<?php

namespace Spatie\SignalAwareCommand;

use Illuminate\Support\Str;

class Signals
{
    public static function getSignalName(int $lookingForSignalNumber): ?string
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

    public static function getSignalForName(string $lookingForSignalName): ?int
    {
        foreach (get_defined_constants(true)['pcntl'] as $signalName => $signalNumber) {
            if ($signalName === $lookingForSignalName) {
                return $signalNumber;
            }
        }

        return null;
    }
}
