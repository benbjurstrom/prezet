<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\GetAllFrontmatter;
use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetSummary;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Collection;

class Prezet
{
    public static function getAllPosts(): Collection
    {
        return GetAllFrontmatter::handle();
    }

    public static function getFrontmatter(string $filepath): FrontmatterData
    {
        return GetFrontmatter::handle($filepath);
    }

    public static function getMarkdown(string $slug): string
    {
        return GetMarkdown::handle($slug);
    }

    public static function setSeo(FrontmatterData $fm): void
    {
        SetSeo::handle($fm);
    }

    public static function getContent(string $md): string
    {
        $content = ParseMarkdown::handle($md);

        return $content->getContent();
    }

    public static function getNav(): array
    {
        return GetSummary::handle();
    }

    public static function getImage(string $path): string
    {
        return GetImage::handle($path);
    }

    public static function getHeadings(string $html): array
    {
        return GetHeadings::handle($html);
    }
}
