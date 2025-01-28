<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage {slug?} {--all}';
    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        if (!$this->argument('slug') && !$this->option('all')) {
            $this->error('Please provide a slug or use --all flag');
            return self::FAILURE;
        }

        $slugs = $this->option('all') 
            ? Document::all()->pluck('slug')
            : collect([$this->argument('slug')]);

        $slugs->each(function ($slug) {
            $imageUrl = Prezet::generateOgImage($slug);
            info('OgImage url: '.$imageUrl);
        });

        return self::SUCCESS;
    }
}
