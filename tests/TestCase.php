<?php

namespace BenBjurstrom\Prezet\Tests;

use ArchTech\SEO\SEOServiceProvider;
use BenBjurstrom\Prezet\PrezetServiceProvider;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

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
            SEOServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('app.debug', 'true');

        config()->set('filesystems.disks.prezet', [
            'driver' => 'local',
            'root' => __DIR__.'/../stubs/prezet',
            'throw' => true,
        ]);

        $dbPath = __DIR__.'/../stubs/prezet.sqlite';
        touch($dbPath);
        config()->set('database.connections.prezet', [
            'driver' => 'sqlite',
            'database' => $dbPath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        config()->set('database.default', 'prezet');

        Route::group([], function () {
            require __DIR__.'/../routes/prezet.php';
        });

        $migrations = __DIR__.'/../database/migrations';
        try {
            Artisan::call('migrate:rollback', [
                '--path' => $migrations,
                '--database' => 'prezet',
                '--realpath' => true,
                '--no-interaction' => true,
            ]);
        } catch (QueryException $e) {
            echo $e->getMessage();
        }

        Artisan::call('migrate', [
            '--path' => $migrations,
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
        ]);
    }
}
