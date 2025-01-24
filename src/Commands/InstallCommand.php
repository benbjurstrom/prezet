<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    use RunsCommands;

    public $signature = 'prezet:install {--force : Force the operation without confirmation}';

    public $description = 'Installs the Prezet package';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('This will modify your project files. Do you wish to continue?')) {
            return self::FAILURE;
        }

        try {
            $this->addStorageDisk();
            $this->addDatabase();
            $this->addRoutes();
            $this->copyContentStubs();
            $this->publishVendorFiles();
            $this->copyTailwindFiles();
            $this->installNodeDependencies();

            // run in separate process so config changes above are applied
            Process::run('php artisan prezet:index --force');
            $this->info('Prezet has been successfully installed!');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('An error occurred during installation: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    protected function addRoutes(): void
    {
        $source = __DIR__.'/../../routes/prezet.php';
        $destination = base_path('routes/prezet.php');
        if (! $this->files->exists($destination)) {
            $this->info('Copying prezet routes');
            $this->files->copy($source, $destination);
        }

        $include = "require __DIR__.'/prezet.php';";
        $web = base_path('routes/web.php');
        $contents = $this->files->get(base_path('routes/web.php'));
        $includePos = strpos($contents, $include);
        if ($includePos !== false) {
            $this->warn('Skipping adding prezet routes to web.php: already exists.');

            return;
        }

        $this->files->append($web, "\nrequire __DIR__.'/prezet.php';");
    }

    protected function copyTailwindFiles(): void
    {
        $this->info('Copying tailwind.prezet.config.js, postcss.config.js, prezet.css, and vite.config.js');
        $this->files->copy(__DIR__.'/../../tailwind.prezet.config.js', base_path('tailwind.prezet.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/prezet.css', resource_path('css/prezet.css'));
        $this->files->copy(__DIR__.'/../../stubs/vite.config.js', base_path('vite.config.js'));

        $this->warn('Please check your vite.config.js to ensure it meets your project requirements.');
    }

    protected function copyContentStubs(): void
    {
        $sourceDir = __DIR__.'/../../stubs/prezet';
        $destinationDir = base_path('prezet');

        if (! $this->files->isDirectory($sourceDir)) {
            $this->warn('Skipping content stubs: source directory already exists.');

            return;
        }
        $this->info('Copying content stubs');

        $this->files->copyDirectory($sourceDir, $destinationDir);
    }

    protected function publishVendorFiles(): void
    {
        $this->info('Publishing vendor files');
        $this->runCommands(['php artisan vendor:publish --provider="BenBjurstrom\Prezet\PrezetServiceProvider" --tag=prezet-views --tag=prezet-config']);
    }

    protected function installNodeDependencies(): void
    {
        $this->info('Installing node dependencies');
        $packages = 'alpinejs @tailwindcss/forms @tailwindcss/typography autoprefixer postcss tailwindcss@3.x vite-plugin-watch-and-run';

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

    protected function addDatabase(): void
    {
        if (config('database.connections.prezet')) {
            $this->warn('Skipping database setup: the prezet database connection already exists.');

            return;
        }
        $this->info('Adding prezet database');
        $configFile = config_path('database.php');
        $config = file_get_contents($configFile);
        if (! $config) {
            $this->error('Failed to read database config file: '.$configFile);

            return;
        }

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'sqlite',\n            'database' => base_path('prezet/prezet.sqlite'),\n            'prefix' => '',\n            'foreign_key_constraints' => true,\n        ],";

        $disksPosition = strpos($config, "'connections' => [");
        if ($disksPosition !== false) {
            $disksPosition += strlen("'connections' => [");
            $newConfig = substr_replace($config, $diskConfig, $disksPosition, 0);
            file_put_contents($configFile, $newConfig);
        }
    }

    protected function addStorageDisk(): void
    {
        if (config('filesystems.disks.prezet')) {
            $this->warn('Skipping storage disk setup: the prezet storage disk already exists.');

            return;
        }
        $this->info('Adding prezet storage disk');

        $configFile = config_path('filesystems.php');
        $config = file_get_contents($configFile);
        if (! $config) {
            $this->error('Failed to read filesystem config file: '.$configFile);

            return;
        }

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'local',\n            'root' => base_path('prezet'),\n            'throw' => false,\n        ],";

        $disksPosition = strpos($config, "'disks' => [");
        if ($disksPosition !== false) {
            $disksPosition += strlen("'disks' => [");
            $newConfig = substr_replace($config, $diskConfig, $disksPosition, 0);
            file_put_contents($configFile, $newConfig);
        }
    }
}
