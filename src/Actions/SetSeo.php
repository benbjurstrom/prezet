<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;

class SetSeo
{
    public static function handle(FrontmatterData $fm): void
    {
        seo()
            ->withUrl()
            ->title($fm->title)
            ->description($fm->excerpt)
            ->image($fm->image)
            ->twitter(!empty($fm->twitter));
    }
}
