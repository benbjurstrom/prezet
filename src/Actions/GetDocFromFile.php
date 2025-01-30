<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetDocFromFile
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
        $doc = Document::query()->where([
            'hash' => $hash,
            'filepath' => $filePath,
        ])->first();

        if ($doc) {
            $docData = app(DocumentData::class)::fromModel($doc);
            $docData->content = $content;

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
            'frontmatter' => $fm,
            'updatedAt' => $this->storage->lastModified($filePath),
            'createdAt' => $fm->date,
        ]);
    }

    protected function getSlug(FrontmatterData $fm, $filepath): string
    {
        // First determine the base slug
        if ($fm->slug) {
            $slug = $fm->slug;
        } else {
            // Generate slug based on config source
            $slug = match (config('prezet.slug.source')) {
                'filepath' => Prezet::getSlugFromFilepath($filepath),
                'title' => Str::slug($fm->title),
                default => Str::slug($fm->title), // fallback to title if config is invalid
            };
        }

        // Optionally append key if configured and key exists
        if (config('prezet.slug.keys') && $fm->key) {
            return $slug.'-'.$fm->key;
        }

        return $slug;
    }

    protected function getFileContent(string $filePath): string
    {
        $content = $this->storage->get($filePath);
        if (! $content) {
            throw new FileNotFoundException($filePath);
        }

        return $content;
    }
}
