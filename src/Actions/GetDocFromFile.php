<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class GetDocFromFile
{
    private Filesystem $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(GetPrezetDisk::handle());
    }

    /**
     * @throws FrontmatterMissingException
     * @throws InvalidConfigurationException
     * @throws FileNotFoundException
     * @throws MissingConfigurationException
     */
    public function handle(string $filePath): DocumentData
    {
        $docClass = $this->getDocumentDataClass();
        $content = $this->getFileContent($filePath);

        $hash = md5($content);
        $slug = $this->getSlug($filePath);
        $doc = Document::query()->where([
            'hash' => $hash,
            'slug' => $slug,
        ])->first();

        if ($doc) {
            $docData = $docClass::fromModel($doc);
            $docData->content = $content;

            return $docData;
        }

        $fm = Prezet::parseFrontmatter($content, $filePath);

        return $docClass::fromArray([
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

    protected function getSlug(string $filePath): string
    {
        $relativePath = trim(str_replace('content', '', $filePath), '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);

        return trim($slug, './');
    }

    protected function getFileContent(string $filePath): string
    {
        $content = $this->storage->get($filePath);
        if (! $content) {
            throw new FileNotFoundException($filePath);
        }

        return $content;
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected function getDocumentDataClass(): string
    {
        $key = 'prezet.data.document';
        $fmClass = Config::string($key);
        if (! class_exists($fmClass)) {
            throw new InvalidConfigurationException($key, $fmClass, 'is not a valid class');
        }

        return $fmClass;
    }
}
