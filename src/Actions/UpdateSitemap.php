<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Models\Document;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UpdateSitemap
{
    public static function handle(): void
    {
        $docs = Document::query()
            ->orderBy('date', 'desc')
            ->where('draft', false)
            ->get();

        $sitemap = Sitemap::create();

        foreach ($docs as $doc) {
            $sitemap->add(Url::create(route('prezet.show', $doc->slug))
                ->setLastModificationDate($doc->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7)
                // ->addVideo($post->video)
            );
        }

        if (config('app.env') !== 'testing') {
            $sitemap->writeToFile(public_path('prezet_sitemap.xml'));
        }
    }
}
