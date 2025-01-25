<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class SetOgImage
{
    public function handle(string $slug, string $imgPath): void
    {
        $doc = Document::where('slug', $slug)->firstOrFail();
        $md = GetMarkdown::handle($doc->filepath);
        $content = ParseMarkdown::handle($md);

        if (! $content instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }
        $fm = $content->getFrontMatter();
        if (! $fm || ! is_array($fm)) {
            throw new FrontmatterMissingException($slug);
        }

        $fm['image'] = $imgPath;
        $newMd = SetFrontmatter::update($md, $fm);

        $storage = Storage::disk(GetPrezetDisk::handle());
        $path = 'content/'.$slug.'.md';
        $storage->put($path, $newMd);
    }
}
