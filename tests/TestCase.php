<?php

namespace BenBjurstrom\Prezet\Tests;

use ArchTech\SEO\SEOServiceProvider;
use BenBjurstrom\Prezet\PrezetServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BenBjurstrom\\Prezet\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            PrezetServiceProvider::class,
            SEOServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.debug', 'true');
        config()->set('filesystems.disks.prezet',[
            'driver' => 'local',
            'root' => base_path('tests/stubs/disk'),
            'throw' => false,
        ]);

        /*
        $migration = include __DIR__.'/../database/migrations/create_prezet_table.php.stub';
        $migration->up();
        */
    }
}
