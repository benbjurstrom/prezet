<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\CreateIndex;
use BenBjurstrom\Prezet\Actions\GenerateOgImage;
use BenBjurstrom\Prezet\Actions\GetAllDocsFromFiles;
use BenBjurstrom\Prezet\Actions\GetDocFromFile;
use BenBjurstrom\Prezet\Actions\GetFlatHeadings;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetLinkedData;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetPrezetDisk;
use BenBjurstrom\Prezet\Actions\GetSummary;
use BenBjurstrom\Prezet\Actions\ParseFrontmatter;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Actions\SetFrontmatter;
use BenBjurstrom\Prezet\Actions\SetOgImage;
use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Actions\UpdateSitemap;
use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use League\CommonMark\Output\RenderedContentInterface;

class Prezet extends Facade
{
    public static function createIndex(): void
    {
        app(CreateIndex::class)->handle();
    }

    public static function generateOgImage(string $mdPath): string
    {
        return app(GenerateOgImage::class)->handle($mdPath);
    }

    /**
     * @return Collection<int,DocumentData>
     */
    public static function getAllDocsFromFiles(): Collection
    {
        return app(GetAllDocsFromFiles::class)->handle();
    }

    public static function getDocFromFile(string $filepath): DocumentData
    {
        return app(GetDocFromFile::class)->handle($filepath);
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    public static function getFlatHeadings(string $html): array
    {
        return app(GetFlatHeadings::class)->handle($html);
    }

    /**
     * @return array<int, array<string, array<int, array<string, string>>|string>>
     */
    public static function getHeadings(string $html): array
    {
        return app(GetHeadings::class)->handle($html);
    }

    public static function getImage(string $path): string
    {
        return app(GetImage::class)->handle($path);
    }

    /**
     * @return array<string, string|array<string, string>>
     */
    public static function getLinkedData(DocumentData $document): array
    {
        return app(GetLinkedData::class)->handle($document);
    }

    public static function getMarkdown(string $filePath): string
    {
        return app(GetMarkdown::class)->handle($filePath);
    }

    public static function getPrezetDisk(): string
    {
        return app(GetPrezetDisk::class)->handle();
    }

    /**
     * @return Collection<int, array{title: string, links: array<int, array<string, string>>}>
     */
    public static function getSummary(?string $filepath = null): Collection
    {
        return app(GetSummary::class)->handle($filepath);
    }

    public static function parseFrontmatter(string $content, string $filePath): FrontmatterData
    {
        return app(ParseFrontmatter::class)->handle($content, $filePath);
    }

    public static function parseMarkdown(string $md): RenderedContentInterface
    {
        return app(ParseMarkdown::class)->handle($md);
    }

    /**
     * @param array<string, mixed> $fm
     */
    public static function setFrontmatter(string $md, array $fm): string
    {
        return app(SetFrontmatter::class)->update($md, $fm);
    }

    public static function setOgImage(string $slug, string $imgPath): void
    {
        app(SetOgImage::class)->handle($slug, $imgPath);
    }

    public static function updateIndex(): void
    {
        app(UpdateIndex::class)->handle();
    }

    public static function updateSitemap(): void
    {
        app(UpdateSitemap::class)->handle();
    }
}
