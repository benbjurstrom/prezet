<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Commands\InstallCommand;
use BenBjurstrom\Prezet\Commands\OgimageCommand;
use BenBjurstrom\Prezet\Commands\UpdateSitemapCommand;
use BenBjurstrom\Prezet\Commands\ValidateFrontmatterCommand;
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
            ->hasViews('prezet')
            ->hasCommands([
                OgimageCommand::class,
                InstallCommand::class,
                UpdateSitemapCommand::class,
                ValidateFrontmatterCommand::class,
            ]);
    }
}
