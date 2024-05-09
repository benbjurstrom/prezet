<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;

class SetSeo
{
    public static function handle(FrontmatterData $fm): void
    {
        seo()
            ->title($fm->title)
            ->description($fm->excerpt)
            ->image($fm->ogimage);
    }
}
