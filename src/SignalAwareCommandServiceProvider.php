<?php

namespace Spatie\SignalAwareCommand;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SignalAwareCommandServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('signal-aware-command');

        $this->app->singleton(Signal::class, function () {
            return new Signal();
        });
    }
}
