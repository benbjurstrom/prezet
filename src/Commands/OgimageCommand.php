<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage {--all} {--slug=}';
    
    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        $mdPaths = [];
        if (! $this->option('all')) {
            $slug = $this->option('slug');
            
            if (!$slug) {
                $slug = text(
                    label: 'Provide the slug for the markdown file you would like to create an og:image for.',
                    required: true,
                );
            }

            $slugs = collect([$slug]);
        } else {
            $slugs = Document::all()->map(function ($doc) {
                return $doc->slug;
            });
        }

        $slugs->each(function ($slug) {
            $imageUrl = Prezet::generateOgImage($slug);
            info('OgImage url: '.$imageUrl);
        });

        return self::SUCCESS;
    }
}
