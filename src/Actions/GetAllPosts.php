<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetAllPosts
{
    public static function handle(): Collection
    {
        $storage = Storage::disk('prezet');
        $ext = new FrontMatterExtension();
        $parser = $ext->getFrontMatterParser();

        $files = collect($storage->allFiles('posts'));

        return $files->map(function ($filePath) use ($parser, $storage) {
            $md = $storage->get($filePath);
            $fm = $parser->parse($md)->getFrontMatter();

            if (! $fm) {
                throw new FrontmatterMissingException($filePath);
            }

            if ($fm['draft'] ?? false) {
                return false;
            }

            $relativePath = trim(str_replace('posts', '', $filePath), '/');
            $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);
            $fm['slug'] = $slug;

            return FrontmatterData::fromArray($fm);
        })->reject(function ($value) {
            return $value === false;
        });
    }
}
