<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Commands\InstallCommand;
use BenBjurstrom\Prezet\Commands\OgimageCommand;
use BenBjurstrom\Prezet\Commands\UpdateIndexCommand;
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
            ->hasMigrations([
                'create_prezet_documents_table',
                'create_prezet_document_tags_table',
                'create_prezet_tags_table',
            ])
            ->runsMigrations()
            ->hasCommands([
                OgimageCommand::class,
                InstallCommand::class,
                UpdateIndexCommand::class,
            ]);
    }
}
