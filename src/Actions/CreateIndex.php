<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateIndex
{
    public static function handle(): void
    {
        $originalPath = Config::string('database.connections.prezet.database');
        $tempPath = sys_get_temp_dir().'/prezet_'.uniqid().'.sqlite';

        try {
            Log::info('Creating new prezet index', ['temp_path' => $tempPath]);

            touch($tempPath);
            Config::set('database.connections.prezet.database', $tempPath);
            DB::purge('prezet');

            self::runMigrations($tempPath);
            self::ensureDirectoryExists($originalPath);

            if (! rename($tempPath, $originalPath)) {
                throw new \RuntimeException("Failed to move database from {$tempPath} to {$originalPath}");
            }

            Log::info('Successfully created new prezet index');

        } catch (\Exception $e) {
            Log::error('Failed to create prezet index', [
                'error' => $e->getMessage(),
                'temp_path' => $tempPath,
                'target_path' => $originalPath,
            ]);
            throw $e;
        } finally {
            Config::set('database.connections.prezet.database', $originalPath);
            DB::purge('prezet');

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }

    protected static function ensureDirectoryExists(string $path): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            if (! mkdir($dir, 0755, true)) {
                throw new \RuntimeException("Failed to create directory: {$dir}");
            }
        }
    }

    protected static function runMigrations(string $path): void
    {
        if (! Schema::connection('prezet')->hasTable('migrations')) {
            Schema::connection('prezet')->create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }

        $result = Artisan::call('migrate', [
            '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
            '--force' => true,
        ]);

        if ($result !== 0) {
            throw new \RuntimeException('Migration failed: '.Artisan::output());
        }
    }
}
