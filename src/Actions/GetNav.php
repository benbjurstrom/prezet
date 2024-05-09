<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetNav
{
    public static function handle(): array
    {
        $ext = new FrontMatterExtension();
        $parser = $ext->getFrontMatterParser();

        $md = Storage::disk('prezet')->get('__nav.md');
        $fm = $parser->parse($md)->getFrontMatter();

        return array_map(function ($section, $links) {
            $formattedLinks = array_map(function ($title, $slug) {
                return [
                    'title' => $title,
                    'slug' => $slug,
                ];
            }, array_keys($links), $links);

            return [
                'title' => $section,
                'links' => array_values($formattedLinks),
            ];
        }, array_keys($fm), $fm);
    }
}
