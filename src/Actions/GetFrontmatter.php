<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetFrontmatter
{
    public static function handle(string $filePath): FrontmatterData
    {
        $fmClass = config('prezet.data.frontmatter');
        $storage = Storage::disk('prezet');

        $md = $storage->get($filePath);

        $ext = new FrontMatterExtension;
        $parser = $ext->getFrontMatterParser();
        $fm = $parser->parse($md)->getFrontMatter();

        if (! $fm) {
            throw new FrontmatterMissingException($filePath);
        }

        $relativePath = trim(str_replace('content', '', $filePath), '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);
        $slug = trim($slug, './');

        $fm['slug'] = $slug;

        $lastModified = $storage->lastModified($filePath);
        $fm['updatedAt'] = $lastModified;

        // depending on the environment, the date may be a string or a timestamp
        if (! empty($fm['date']) && is_string($fm['date'])) {
            $fm['date'] = strtotime($fm['date']);
        }

        return $fmClass::fromArray($fm);
    }
}
