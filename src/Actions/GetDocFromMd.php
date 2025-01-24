<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class GetDocFromMd
{
    /**
     * @throws FrontmatterMissingException
     * @throws InvalidConfigurationException
     * @throws FileNotFoundException
     * @throws MissingConfigurationException
     */
    public static function handle(string $filePath): DocumentData
    {
        $docClass = self::getDocumentDataClass();
        $storage = Storage::disk(GetPrezetDisk::handle());
        $content = self::getFileContent($filePath, $storage);

        $hash = md5($content);
        $slug = self::getSlug($filePath);
        $doc = Document::query()->where([
            'hash' => $hash,
            'slug' => $slug,
        ])->first();
        if ($doc) {
            return $docClass::fromModel($doc);
        }

        $fm = ParseFrontmatter::handle($content, $filePath);

        return $docClass::fromArray([
            'slug' => $slug,
            'hash' => $hash,
            'draft' => $fm->draft,
            'content' => $content,
            'category' => $fm->category,
            'frontmatter' => $fm,
            'updatedAt' => $storage->lastModified($filePath),
            'createdAt' => $fm->date,
        ]);
    }

    protected static function getSlug(string $filePath): string
    {
        $relativePath = trim(str_replace('content', '', $filePath), '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);

        return trim($slug, './');
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
     * @throws InvalidConfigurationException
     */
    protected static function getDocumentDataClass(): string
    {
        $key = 'prezet.data.document';
        $fmClass = Config::string($key);
        if (! class_exists($fmClass)) {
            throw new InvalidConfigurationException($key, $fmClass, 'is not a valid class');
        }

        return $fmClass;
    }
}
