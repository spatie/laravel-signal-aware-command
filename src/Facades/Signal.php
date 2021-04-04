<?php

namespace Spatie\SignableCommand\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\SignableCommand\Signal as SignalClass;

/**
 * @see \Spatie\SignableCommand\Signal
 */
class Signal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SignalClass::class;
    }
}
