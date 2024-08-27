<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    use RunsCommands;

    public $signature = 'prezet:install';

    public $description = 'Installs the Prezet package';

    public function handle(): int
    {
        // Add the prezet disk to the filesystems config
        $this->addStorageDisk();
        $this->addDatabase();
        $this->addRoutes();
        $this->copyContentStubs();
        $this->publishVendorFiles();
        $this->copyTailwindFiles();
        $this->installNodeDependencies();

        return self::SUCCESS;
    }

    protected function addRoutes(): void
    {
        $files = new Filesystem;
        $files->copy(__DIR__.'/../../routes/prezet.php', base_path('routes/prezet.php'));

        $web = $files->get(base_path('routes/web.php'));
        $includePos = strpos($web, "require __DIR__.'/prezet.php';");
        if ($includePos !== false) {
            return;
        }

        $files->append(base_path('routes/web.php'), "\nrequire __DIR__.'/prezet.php';");
    }

    protected function copyTailwindFiles(): void
    {
        $files = new Filesystem;
        $files->copy(__DIR__.'/../../tailwind.prezet.config.js', base_path('tailwind.prezet.config.js'));
        $files->copy(__DIR__.'/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        $files->copy(__DIR__.'/../../stubs/prezet.css', resource_path('css/prezet.css'));
        $files->copy(__DIR__.'/../../stubs/vite.config.js', base_path('vite.config.js'));

        $this->info('Copied tailwind.prezet.config.js, postcss.config.js, prezet.css, and vite.config.js');
        $this->warn('Please check your vite.config.js to ensure it meets your project requirements.');
    }

    protected function copyContentStubs(): void
    {
        $files = new Filesystem;
        $files->copyDirectory(__DIR__.'/../../stubs/prezet', storage_path('prezet'));
    }

    protected function publishVendorFiles(): void
    {
        $this->runCommands(['php artisan vendor:publish --provider="BenBjurstrom\Prezet\PrezetServiceProvider" --tag=prezet-views --tag=prezet-config']);
    }

    protected function installNodeDependencies(): void
    {
        $packages = 'alpinejs @tailwindcss/forms @tailwindcss/typography autoprefixer postcss tailwindcss';

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $bin = 'pnpm';
        } elseif (file_exists(base_path('yarn.lock'))) {
            $bin = 'yarn';
        } else {
            $bin = 'npm';
        }

        $this->runCommands([
            $bin.' install --save-dev '.$packages,
            $bin.' run build',
        ]);
    }

    protected function addDatabase(): bool
    {
        if (config('database.connections.prezet')) {
            return false;
        }

        $files = new Filesystem;
        $files->copy(__DIR__.'/../../stubs/prezet.sqlite', base_path('prezet.sqlite'));

        $configFile = config_path('database.php');
        $config = file_get_contents($configFile);

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'sqlite',\n            'database' => base_path('prezet.sqlite'),\n            'prefix' => '',\n            'foreign_key_constraints' => true,\n        ],";

        $disksPosition = strpos($config, "'connections' => [");
        if ($disksPosition !== false) {
            $disksPosition += strlen("'connections' => [");
            $newConfig = substr_replace($config, $diskConfig, $disksPosition, 0);
            file_put_contents($configFile, $newConfig);

            return true;
        }

        return false;
    }

    protected function addStorageDisk(): bool
    {
        if (config('filesystems.disks.prezet')) {
            return false;
        }

        $configFile = config_path('filesystems.php');
        $config = file_get_contents($configFile);

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'local',\n            'root' => storage_path('prezet'),\n            'throw' => false,\n        ],";

        $disksPosition = strpos($config, "'disks' => [");
        if ($disksPosition !== false) {
            $disksPosition += strlen("'disks' => [");
            $newConfig = substr_replace($config, $diskConfig, $disksPosition, 0);
            file_put_contents($configFile, $newConfig);

            return true;
        }

        return false;
    }
}
