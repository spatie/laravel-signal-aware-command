<?php

namespace Spatie\SignableCommand;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\SignableCommand\Commands\SignableCommandCommand;

class SignableCommandServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {

        $package->name('signable-command');
    }
}
