<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Data\FrontmatterData;
use Prezet\Prezet\Exceptions\FileNotFoundException;
use Prezet\Prezet\Exceptions\FrontmatterMissingException;
use Prezet\Prezet\Exceptions\InvalidConfigurationException;
use Prezet\Prezet\Exceptions\MissingConfigurationException;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Prezet;

class GetDocumentDataFromFile
{
    private Filesystem $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(Prezet::getPrezetDisk());
    }

    /**
     * @throws FrontmatterMissingException
     * @throws InvalidConfigurationException
     * @throws FileNotFoundException
     * @throws MissingConfigurationException
     */
    public function handle(string $filePath): DocumentData
    {
        $content = $this->getFileContent($filePath);
        $hash = md5($content);

        if ($docData = $this->unchanged($hash, $filePath, $content)) {
            return $docData;
        }

        $fm = Prezet::parseFrontmatter($content, $filePath);

        $slug = $this->getSlug($fm, $filePath);

        return app(DocumentData::class)::fromArray([
            'key' => $fm->key,
            'filepath' => $filePath,
            'slug' => $slug,
            'hash' => $hash,
            'draft' => $fm->draft,
            'content' => $content,
            'category' => $fm->category,
            'contentType' => $fm->contentType,
            'frontmatter' => $fm,
            'updatedAt' => $this->storage->lastModified($filePath),
            'createdAt' => $fm->date,
        ]);
    }

    protected function getFileContent(string $filePath): string
    {
        $content = $this->storage->get($filePath);
        if (! $content) {
            throw new FileNotFoundException($filePath);
        }

        return $content;
    }

    protected function unchanged(string $hash, string $filePath, string $content): ?DocumentData
    {
        $doc = app(Document::class)::query()->where([
            'hash' => $hash,
            'filepath' => $filePath,
        ])->first();

        if ($doc) {
            $docData = app(DocumentData::class)::fromModel($doc);
            $docData->content = $content;

            return $docData;
        }

        return null;
    }

    protected function getSlug(FrontmatterData $fm, string $filepath): string
    {
        // First determine the base slug
        $slug = $this->getBaseSlug($fm, $filepath);

        // Then optionally append the key if configured and key exists
        if (Config::boolean('prezet.slug.keyed') && $fm->key) {
            return $slug.'-'.$fm->key;
        }

        return $slug;
    }

    protected function getBaseSlug(FrontmatterData $fm, string $filepath): string
    {
        // If slug is defined in front matter, use it
        if ($fm->slug) {
            return $fm->slug;
        }

        // If source is title, slugify the title
        if (Config::string('prezet.slug.source') === 'title') {
            return Str::slug($fm->title);
        }

        // Otherwise use the file path
        return Prezet::getSlugFromFilepath($filepath);
    }
}
