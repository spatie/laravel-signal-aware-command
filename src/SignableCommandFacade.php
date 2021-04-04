<?php

namespace Spatie\SignableCommand;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\SignableCommand\SignableCommand
 */
class SignableCommandFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel_signable_command';
    }
}
