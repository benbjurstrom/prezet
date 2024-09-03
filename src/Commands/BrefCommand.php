<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class BrefCommand extends Command
{
    use RunsCommands;

    protected $signature = 'prezet:bref';

    protected $description = 'Configures your application for use with Bref';

    public function handle(): void
    {
        $domain = text(
            label: 'Please enter the domain name for your application',
            placeholder: 'example.com',
            required: 'A domain name is required.',
            validate: function ($value) {
                if (! preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', $value)) {
                    return 'Please enter a valid domain name.';
                }
            }
        );

        //        $parts = explode('.', $domain);
        //        $host = implode('.', array_slice($parts, 0, -1));

        $service = Str::slug(title: $domain, dictionary: ['.' => '-']);

        $this->installComposerPackages();
        $this->installNodeDependencies();
        $this->copyServerlessStub($service, $domain);

        $this->info("You entered the domain: $service");

        // Here you can add any additional logic to use the domain name
        // For example, saving it to a configuration file or database
    }

    protected function copyServerlessStub(string $service, string $domain): void
    {
        $files = new Filesystem;
        $template = $files->get(__DIR__.'/../../stubs/serverless.yml');
        $content = str_replace('{SERVICE_NAME}', $service, $template);
        $content = str_replace('{DOMAIN_NAME}', $domain, $content);
        $files->put(base_path('serverless.yml'), $content);
    }

    protected function installComposerPackages(): void
    {
        // TODO: what if composer is not in the path?
        $this->runCommands(['composer require league/flysystem-aws-s3-v3 league/flysystem-read-only bref/bref bref/extra-php-extensions bref/laravel-bridge']);
    }

    protected function installNodeDependencies(): void
    {
        $packages = 'serverless-lift serverless-s3-sync serverless-api-gateway-throttling';

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
}
