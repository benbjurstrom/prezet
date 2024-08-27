<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class InstallCommand extends Command
{
    use RunsCommands;

    public $signature = 'prezet:install {--force : Force the operation without confirmation}';

    public $description = 'Installs the Prezet package';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        if (!$this->option('force') && !$this->confirm('This will modify your project files. Do you wish to continue?')) {
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

            $this->info('Prezet has been successfully installed!');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('An error occurred during installation: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function addRoutes(): void
    {
        $source = __DIR__.'/../../routes/prezet.php';
        $destination = base_path('routes/prezet.php');
        if(!$this->files->exists($destination)){
            $this->files->copy($source, $destination);
        }

        $include = "require __DIR__.'/prezet.php';";
        $web = base_path('routes/web.php');
        $contents = $this->files->get(base_path('routes/web.php'));
        $includePos = strpos($contents, $include);
        if ($includePos !== false) {
            return;
        }

        $this->files->append($web, "\nrequire __DIR__.'/prezet.php';");
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

    protected function addDatabase(): void
    {
        if (config('database.connections.prezet')) {
            return;
        }

        $this->files->copy(__DIR__.'/../../stubs/prezet.sqlite', base_path('prezet.sqlite'));

        $configFile = config_path('database.php');
        $config = file_get_contents($configFile);

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'sqlite',\n            'database' => base_path('prezet.sqlite'),\n            'prefix' => '',\n            'foreign_key_constraints' => true,\n        ],";

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
            return;
        }

        $configFile = config_path('filesystems.php');
        $config = file_get_contents($configFile);

        $diskConfig = "\n        'prezet' => [\n            'driver' => 'local',\n            'root' => storage_path('prezet'),\n            'throw' => false,\n        ],";

        $disksPosition = strpos($config, "'disks' => [");
        if ($disksPosition !== false) {
            $disksPosition += strlen("'disks' => [");
            $newConfig = substr_replace($config, $diskConfig, $disksPosition, 0);
            file_put_contents($configFile, $newConfig);
        }
    }
}
