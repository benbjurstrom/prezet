<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UpdateSitemapCommand extends Command
{
    public $signature = 'prezet:update-sitemap';

    public $description = 'Updates the prezet_sitemap.xml file.';

    public function handle(): int
    {
        $docs = Document::query()
            ->orderBy('date', 'desc')
            ->where('draft', false)
            ->get();

        $sitemap = Sitemap::create();

        foreach ($docs as $doc) {
            $sitemap->add(Url::create('/'.$doc->slug)
                ->setLastModificationDate($doc->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7)
                // ->addVideo($post->video)
            );
        }

        $sitemap->writeToFile(public_path('prezet_sitemap.xml'));

        return self::SUCCESS;
    }
}
