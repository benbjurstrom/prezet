<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use Prezet\Prezet\Exceptions\FrontmatterMissingException;
use Prezet\Prezet\Prezet;

class SetKey
{
    public function handle(string $filepath, string $key): void
    {
        // Get the markdown content
        $md = Prezet::getMarkdown($filepath);
        $content = Prezet::parseMarkdown($md);

        if (! $content instanceof RenderedContentWithFrontMatter) {
            throw new FrontmatterMissingException($filepath);
        }

        $fm = $content->getFrontMatter();
        if (! $fm || ! is_array($fm)) {
            throw new FrontmatterMissingException($filepath);
        }

        // Add the key to frontmatter
        $fm['key'] = $key;
        $newMd = Prezet::setFrontmatter($md, $fm);

        // Save the updated markdown
        $storage = Storage::disk(Prezet::getPrezetDisk());
        $storage->put($filepath, $newMd);
    }
}
