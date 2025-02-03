<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    use RunsCommands;

    public $signature = 'prezet:install {--force : Force the operation without confirmation} {--tailwind3 : Install Tailwind CSS v3 instead of v4}';

    public $description = 'Installs the Prezet package';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        // Skip all checks if force flag is set
        if ($this->option('force')) {
            return $this->runInstall();
        }

        try {
            $gitStatus = $this->checkGitStatus();

            // If git repo is dirty, exit with error
            if ($gitStatus === 'dirty') {
                $this->error('Git directory is not clean. Please stash or commit your changes before installing.');

                return self::FAILURE;
            }

            // If no git repo or clean repo, proceed with appropriate confirmation
            if ($gitStatus === 'no_git' && ! $this->confirm('No git repository detected. This will modify your project files. Do you wish to continue?')) {
                return self::FAILURE;
            }

            // If we get here, either the repo is clean or user confirmed to proceed
            return $this->runInstall();

        } catch (\Exception $e) {
            $this->error('An error occurred while checking git status: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    protected function checkGitStatus(): string
    {
        try {
            $process = Process::run('git status --porcelain');

            if ($process->exitCode() !== 0) {
                // Not a git repository
                return 'no_git';
            }

            $output = $process->output();

            // If no changes, return clean
            if ($output === '') {
                return 'clean';
            }

            // Check if only composer files are modified
            $changes = array_filter(explode("\n", trim($output)));
            $onlyComposerFiles = true;

            foreach ($changes as $change) {
                if (! str_contains($change, 'composer')) {
                    $onlyComposerFiles = false;
                    break;
                }
            }

            return $onlyComposerFiles ? 'clean' : 'dirty';
        } catch (\Exception $e) {
            // If process fails for any reason, assume no git
            return 'no_git';
        }
    }

    protected function runInstall(): int
    {
        try {
            $this->addStorageDisk();
            $this->addDatabase();
            $this->addRoutes();
            $this->copyContentStubs();
            $this->publishVendorFiles();
            $this->option('tailwind3') ? $this->copyTailwind3Files() : $this->copyTailwindFiles();

            $this->installNodeDependencies();

            // run in separate process so config changes above are applied
            Process::run('php artisan prezet:index --fresh');
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
        $this->info('Copying Tailwind configuration files');
        $this->files->copy(__DIR__.'/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/prezet.css', resource_path('css/prezet.css'));
        $this->files->copy(__DIR__.'/../../stubs/vite.config.js', base_path('vite.config.js'));

        // Copy appropriate app.css based on whether tailwind.config.js exists
        if (file_exists(base_path('tailwind.config.js'))) {
            $this->files->copy(__DIR__.'/../../stubs/app-config.css', resource_path('css/app.css'));
        } else {
            $this->files->copy(__DIR__.'/../../stubs/app.css', resource_path('css/app.css'));
        }

        $this->warn('Please check your vite.config.js to ensure it meets your project requirements.');
    }

    protected function copyTailwind3Files(): void
    {
        $this->info('Copying Tailwind3 configuration files');
        $this->files->copy(__DIR__.'/../../stubs/tailwind3/tailwind.prezet.config.js', base_path('tailwind.prezet.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/tailwind3/postcss.config.js', base_path('postcss.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/tailwind3/prezet.css', resource_path('css/prezet.css'));
        $this->files->copy(__DIR__.'/../../stubs/tailwind3/vite.config.js', base_path('vite.config.js'));

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

        if ($this->option('tailwind3')) {
            $packages = 'alpinejs @tailwindcss/forms @tailwindcss/typography autoprefixer postcss tailwindcss@3.x vite-plugin-watch-and-run';
        } else {
            $packages = 'alpinejs @tailwindcss/forms @tailwindcss/typography @tailwindcss/vite tailwindcss@4 vite-plugin-watch-and-run @tailwindcss/postcss';
        }

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
