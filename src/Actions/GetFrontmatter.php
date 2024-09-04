<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetFrontmatter
{
    /**
     * @throws FileNotFoundException | FrontmatterMissingException|MissingConfigurationException|InvalidConfigurationException
     */
    public static function handle(string $filePath): FrontmatterData
    {
        $fmClass = config('prezet.data.frontmatter');
        if (! is_string($fmClass) || ! class_exists($fmClass)) {
            throw new InvalidConfigurationException('prezet.data.frontmatter', $fmClass, 'is not a valid class');
        }

        $storage = Storage::disk(GetPrezetDisk::handle());

        $md = $storage->get($filePath);
        if (! $md) {
            throw new FileNotFoundException($filePath);
        }

        $ext = new FrontMatterExtension;
        $parser = $ext->getFrontMatterParser();
        $fm = $parser->parse($md)->getFrontMatter();

        if (! $fm || ! is_array($fm)) {
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
