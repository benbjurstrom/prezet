<?php

namespace BenBjurstrom\Prezet\Commands;

use BenBjurstrom\Prezet\Actions\GetAllPosts;
use Illuminate\Console\Command;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UpdateSitemapCommand extends Command
{
    public $signature = 'prezet:update-sitemap';

    public $description = 'Updates the prezet_sitemap.xml file.';

    public function handle(): int
    {
        $result = GetAllPosts::handle();
        $sitemap = Sitemap::create();

            foreach ($result as $post) {
                $sitemap->add(Url::create('/' . $post->slug)
                    ->setLastModificationDate($post->date)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
                    // ->addVideo($post->video)
                );
            }

        $sitemap->writeToFile(public_path('prezet_sitemap.xml'));

        return self::SUCCESS;
    }
}
