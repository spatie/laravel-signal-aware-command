<?php

namespace Spatie\SignableCommand;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SignableCommandServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('signable-command');

        $this->app->singleton(Signal::class, function() {
            return new Signal();
        });

    }
}
