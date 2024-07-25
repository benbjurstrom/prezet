<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class SetOgImage
{
    public static function handle(string $slug, string $imgPath): void
    {
        $md = GetMarkdown::handle($slug);
        $content = ParseMarkdown::handle($md);

        $fm = $content->getFrontMatter();
        $fm['ogimage'] = $imgPath;
        $newMd = SetFrontmatter::update($md, $fm);

        $storage = Storage::disk('prezet');
        $path = 'content/'.$slug.'.md';
        $storage->put($path, $newMd);
    }
}
