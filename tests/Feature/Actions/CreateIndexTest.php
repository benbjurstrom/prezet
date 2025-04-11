<?php

namespace Prezet\Prezet\Tests\Feature\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Prezet\Prezet\Prezet;

beforeEach(function () {
    // Get the test database path from the prezet connection
    $this->dbPath = Config::get('database.connections.prezet.database');

    // Ensure we start with a clean state
    if (file_exists($this->dbPath)) {
        unlink($this->dbPath);
    }
    DB::purge('prezet');
});

test('creates a new index database', function () {
    // Note that Artisan calls, like the one in CreateIndex do not run in tests.

    Prezet::CreateIndex();
    // Assert
    expect(file_exists($this->dbPath))->toBeTrue()
        ->and(Schema::connection('prezet')->hasTable('migrations'))->toBeTrue();
});

test('creates parent directory if it doesnt exist', function () {
    // Arrange
    $nestedPath = sys_get_temp_dir().'/prezet_test_dir/nested/db.sqlite';
    $originalPath = Config::get('database.connections.prezet.database');
    Config::set('database.connections.prezet.database', $nestedPath);
    DB::purge('prezet');

    try {
        // Act
        Prezet::CreateIndex();

        // Assert
        expect(file_exists($nestedPath))->toBeTrue()
            ->and(is_dir(dirname($nestedPath)))->toBeTrue();

    } finally {
        // Cleanup
        if (file_exists($nestedPath)) {
            unlink($nestedPath);
        }
        if (is_dir(dirname($nestedPath))) {
            rmdir(dirname($nestedPath));
            rmdir(dirname(dirname($nestedPath)));
        }

        // Restore original config
        Config::set('database.connections.prezet.database', $originalPath);
        DB::purge('prezet');
    }
});

test('cleans up temporary files on failure', function () {
    // Arrange
    $invalidPath = '/invalid/path/that/cant/exist/db.sqlite';
    $originalPath = Config::get('database.connections.prezet.database');
    Config::set('database.connections.prezet.database', $invalidPath);
    DB::purge('prezet');

    try {
        // Act
        Prezet::CreateIndex();
    } catch (\Exception $e) {
        // Exception expected
    } finally {
        // Restore original config
        Config::set('database.connections.prezet.database', $originalPath);
        DB::purge('prezet');
    }

    // Assert
    $tempFiles = glob(sys_get_temp_dir().'/prezet_*');
    expect($tempFiles)->toBeEmpty('Temporary files should be cleaned up');
});
