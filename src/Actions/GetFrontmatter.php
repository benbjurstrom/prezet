<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetFrontmatter
{
    /**
     * @throws FrontmatterMissingException
     * @throws InvalidConfigurationException
     * @throws FileNotFoundException
     * @throws MissingConfigurationException
     */
    public static function handle(string $filePath): FrontmatterData
    {
        $storage = Storage::disk(GetPrezetDisk::handle());
        $content = self::getFileContent($filePath, $storage);

        $frontmatter = self::parseFrontmatter($content, $filePath);
        $frontmatter = self::addSlugToFrontmatter($frontmatter, $filePath);
        $frontmatter = self::addLastModifiedToFrontmatter($frontmatter, $filePath, $storage);
        $frontmatter = self::normalizeDateInFrontmatter($frontmatter);

        $fmClass = self::getFrontMatterClass();

        return $fmClass::fromArray($frontmatter);
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected static function getFrontMatterClass(): string
    {
        $key = 'prezet.data.frontmatter';
        $fmClass = config($key);
        if (! is_string($fmClass) || ! class_exists($fmClass)) {
            throw new InvalidConfigurationException($key, $fmClass, 'is not a valid class');
        }

        return $fmClass;
    }

    protected static function getFileContent(string $filePath, Filesystem $storage): string
    {
        $content = $storage->get($filePath);
        if (! $content) {
            throw new FileNotFoundException($filePath);
        }

        return $content;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws FrontmatterMissingException
     */
    protected static function parseFrontmatter(string $content, string $filePath): array
    {
        $ext = new FrontMatterExtension;
        $parser = $ext->getFrontMatterParser();
        $frontmatter = $parser->parse($content)->getFrontMatter();

        if (! $frontmatter || ! is_array($frontmatter)) {
            throw new FrontmatterMissingException($filePath);
        }

        return $frontmatter;
    }

    /**
     * @param  array<string, mixed>  $frontmatter
     * @return array<string, mixed>
     */
    protected static function addSlugToFrontmatter(array $frontmatter, string $filePath): array
    {
        $relativePath = trim(str_replace('content', '', $filePath), '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);
        $frontmatter['slug'] = trim($slug, './');

        return $frontmatter;
    }

    /**
     * @param  array<string, mixed>  $frontmatter
     * @return array<string, mixed>
     *
     * @throws FrontmatterMissingException
     */
    protected static function addLastModifiedToFrontmatter(array $frontmatter, string $filePath, Filesystem $storage): array
    {
        $lastModified = $storage->lastModified($filePath);
        $frontmatter['updatedAt'] = $lastModified;

        return $frontmatter;
    }

    /**
     * @param  array<string, mixed>  $frontmatter
     * @return array<string, mixed>
     */
    protected static function normalizeDateInFrontmatter(array $frontmatter): array
    {
        if (! empty($frontmatter['date']) && is_string($frontmatter['date'])) {
            $frontmatter['date'] = strtotime($frontmatter['date']);
        }

        return $frontmatter;
    }
}
