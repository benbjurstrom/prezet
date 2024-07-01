<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    public $signature = 'prezet:install';

    public $description = 'Installs the Prezet package';

    public function handle(): int
    {
        // Add the prezet disk to the filesystems config
        $this->addStorageDisk();
        $this->addRoutes();
        $this->copyContentStubs();
        $this->publishVendorFiles();
        $this->copyTailwindFiles();
        $this->installNodeDependencies();

        return self::SUCCESS;
    }

    protected function addRoutes()
    {
        $files = new Filesystem;
        $files->copy(__DIR__.'/../../routes/prezet.php', base_path('routes/prezet.php'));

        $web = $files->get(base_path('routes/web.php'));
        $includePos = strpos($web, "require __DIR__.'/prezet.php';");
        if($includePos !== false) {
            return;
        }

        $files->append(base_path('routes/web.php'), "\nrequire __DIR__.'/prezet.php';");
    }

    protected function copyTailwindFiles()
    {
        $files = new Filesystem;
        $files->copy(__DIR__.'/../../tailwind.config.js', base_path('tailwind.config.js'));
        $files->copy(__DIR__.'/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        $files->copy(__DIR__.'/../../stubs/app.css', resource_path('css/app.css'));
    }

    protected function copyContentStubs()
    {
        $files = new Filesystem;
        $files->copyDirectory(__DIR__.'/../../stubs/prezet', storage_path('prezet'));
    }

    protected function publishVendorFiles()
    {
        $this->runCommands(['php artisan vendor:publish --provider="BenBjurstrom\Prezet\PrezetServiceProvider" --tag=prezet-views --tag=prezet-config']);
    }

    protected function installNodeDependencies()
    {
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.7',
                '@tailwindcss/typography' => '^0.5.13',
                'alpinejs' => '^3.4.2',
                'autoprefixer' => '^10.4.19',
                'postcss' => '^8.4.38',
                'tailwindcss' => '^3.4.3',
            ] + $packages;
        });

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }
    }

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected function addStorageDisk()
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

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}
