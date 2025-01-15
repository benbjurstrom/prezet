<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIndex
{
    public static function handle(): void
    {
        // Store the original database path
        $originalPath = Config::get('database.connections.prezet.database');

        // Create a temporary path for the new database
        $tempPath = sys_get_temp_dir() . '/prezet_' . uniqid() . '.sqlite';

        try {
            touch($tempPath);
            // Update config to use temporary path
            Config::set('database.connections.prezet.database', $tempPath);
            DB::purge('prezet');

            // Create migrations table if it doesn't exist
            if (! Schema::connection('prezet')->hasTable('migrations')) {
                Schema::connection('prezet')->create('migrations', function ($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });
            }

            // Run migrations on the temporary database
            Artisan::call('migrate:fresh', [
                '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
                '--database' => 'prezet',
                '--realpath' => true,
                '--no-interaction' => true,
            ]);

            // Ensure the directory exists
            $targetDir = dirname($originalPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Move the temporary database to the final location
            rename($tempPath, $originalPath);

        } finally {
            // Reset the config to original path
            Config::set('database.connections.prezet.database', $originalPath);
            DB::purge('prezet');

            // Clean up temporary file if it still exists
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }
}
