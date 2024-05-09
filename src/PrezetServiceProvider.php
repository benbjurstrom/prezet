<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Commands\PrezetCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PrezetServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('prezet')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_prezet_table')
            ->hasCommand(PrezetCommand::class);
    }
}
