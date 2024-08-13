<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class SetOgImage
{
    public static function handle(string $slug, string $imgPath): void
    {
        $doc = Document::where('slug', $slug)->firstOrFail();
        $md = GetMarkdown::handle($doc->filepath);
        $content = ParseMarkdown::handle($md);

        if (! $content instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }
        $fm = $content->getFrontMatter();
        $fm['image'] = $imgPath;
        $newMd = SetFrontmatter::update($md, $fm);

        $storage = Storage::disk(config('prezet.filesystem.disk'));
        $path = 'content/'.$slug.'.md';
        $storage->put($path, $newMd);
    }
}
