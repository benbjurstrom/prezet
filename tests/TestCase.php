<?php

namespace Prezet\Prezet\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Prezet\Prezet\PrezetServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            PrezetServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('app.debug', 'true');

        config()->set('filesystems.disks.prezet', [
            'driver' => 'local',
            'root' => __DIR__.'/Resources/content',
            'throw' => true,
        ]);

        $dbPath = __DIR__.'/Resources/prezet.sqlite';
        touch($dbPath);
        config()->set('database.connections.prezet', [
            'driver' => 'sqlite',
            'database' => $dbPath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        config()->set('database.default', 'prezet');

        Route::group([], function () {
            require __DIR__.'/Resources/routes.php';
        });

        $migrations = __DIR__.'/../database/migrations';
        Artisan::call('migrate:fresh', [
            '--path' => $migrations,
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
        ]);
    }
}
