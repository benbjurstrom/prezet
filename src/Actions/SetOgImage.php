<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class SetOgImage
{
    public function handle(string $slug, string $imgPath): void
    {
        $doc = Document::where('slug', $slug)->firstOrFail();
        $md = Prezet::getMarkdown($doc->filepath);
        $content = Prezet::parseMarkdown($md);

        if (! $content instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }
        $fm = $content->getFrontMatter();
        if (! $fm || ! is_array($fm)) {
            throw new FrontmatterMissingException($slug);
        }

        $fm['image'] = $imgPath;
        $newMd = Prezet::setFrontmatter($md, $fm);

        $storage = Storage::disk(Prezet::getPrezetDisk());
        $path = 'content/'.$slug.'.md';
        $storage->put($path, $newMd);
    }
}
