<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UpdateSitemap
{
    public function handle(): void
    {
        $this->ensureRoutesAreRegistered();

        $docs = app(Document::class)::query()
            ->orderBy('date', 'desc')
            ->where('draft', false)
            ->get();

        $sitemap = Sitemap::create();

        foreach ($docs as $doc) {
            if (! $doc instanceof Document) {
                throw new \Exception('Invalid document');
            }

            $sitemapUrl = config('prezet.sitemap.origin');
            $sitemap->add(Url::create($sitemapUrl.route('prezet.show', $doc->slug, false))
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

    public function ensureRoutesAreRegistered(): void
    {
        Route::get('prezet/{slug}', function ($slug) {})
            ->name('prezet.show')
            ->where('slug', '.*');
    }
}
