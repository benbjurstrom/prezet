<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

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
