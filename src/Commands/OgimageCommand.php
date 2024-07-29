<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Actions\GenerateOgImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage {--all}';

    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        $mdPaths = [];
        if (! $this->option('all')) {
            $mdPath = text(
                label: 'Which markdown file would you like to generate an og:image for?',
                default: 'welcome-to-prezet',
                required: true,
            );

            $mdPaths = [$mdPath];
        } else {
            $mdPaths = Storage::disk('prezet')->files('content');
            $mdPaths = array_map(function ($path) {
                return Str::before(Str::after($path, 'content/'), '.md');
            }, $mdPaths);
        }

        foreach ($mdPaths as $mdPath) {
            $imageUrl = GenerateOgImage::handle($mdPath);
            info('OgImage url: '.$imageUrl);
        }

        return self::SUCCESS;
    }

    protected function validatePath($mdPath)
    {
        $mdPath = Str::rtrim($mdPath, '.md');

        // verify the file exists
        if (! Storage::disk('prezet')->exists('content/'.$mdPath.'.md')) {
            throw new \Exception('File does not exist');
        }

        return $mdPath;
    }
}
