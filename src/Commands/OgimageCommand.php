<?php

namespace Prezet\Prezet\Commands;

use Illuminate\Console\Command;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Prezet;

use function Laravel\Prompts\info;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage {slug?} {--all}';

    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        if (! $this->argument('slug') && ! $this->option('all')) {
            $this->error('Please provide a slug or use --all flag');

            return self::FAILURE;
        }

        $slugs = $this->option('all')
            ? app(Document::class)::all()->pluck('slug')
            : collect([$this->argument('slug')]);

        $slugs->each(function ($slug) {
            if (! is_string($slug)) {
                throw new \InvalidArgumentException('The given slug must be a string');
            }

            $imageUrl = Prezet::generateOgImage($slug);
            info('OgImage url: '.$imageUrl);
        });

        return self::SUCCESS;
    }
}
