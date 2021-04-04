<?php

namespace Spatie\SignalAwareCommand\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\SignalAwareCommand\Signal as SignalClass;

/**
 * @see \Spatie\SignalAwareCommand\Signal
 */
class Signal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SignalClass::class;
    }
}
