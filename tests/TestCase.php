<?php

namespace BenBjurstrom\Prezet\Tests;

use ArchTech\SEO\SEOServiceProvider;
use BenBjurstrom\Prezet\PrezetServiceProvider;
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
            'root' => base_path('tests/stubs/disk'),
            'throw' => false,
        ]);
    }
}
