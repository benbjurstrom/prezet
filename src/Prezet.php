<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\GetDocFromMd;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetSummary;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Data\DocumentData;
use Illuminate\Support\Collection;

class Prezet
{
    public static function getFrontmatter(string $filepath): DocumentData
    {
        return GetDocFromMd::handle($filepath);
    }

    public static function getMarkdown(string $filePath): string
    {
        return GetMarkdown::handle($filePath);
    }

    public static function getContent(string $md): string
    {
        $content = ParseMarkdown::handle($md);

        return $content->getContent();
    }

    /**
     * @return Collection<int, array{title: string, links: array<int, array<string, string>>}>
     */
    public static function getNav(?string $filepath = null): Collection
    {
        return GetSummary::handle($filepath);
    }

    public static function getImage(string $path): string
    {
        return GetImage::handle($path);
    }

    /**
     * @return array<int, array<string, array<int, array<string, string>>|string>>
     */
    public static function getHeadings(string $html): array
    {
        return GetHeadings::handle($html);
    }
}
