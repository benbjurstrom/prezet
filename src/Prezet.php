<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\GetAllPosts;
use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetSummary;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Collection;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Output\RenderedContentInterface;

class Prezet
{
    public static function getAllPosts(): Collection
    {
        return GetAllPosts::handle();
    }

    public static function getMarkdown(string $slug): string
    {
        return GetMarkdown::handle($slug);
    }

    public static function setSeo(FrontmatterData $fm): void
    {
        SetSeo::handle($fm);
    }

    public static function getHtml(RenderedContentInterface $content): string
    {
        return $content->getContent();
    }

    public static function getFrontmatter(
        RenderedContentInterface $content,
        string $slug
    ): FrontmatterData {
        if (! $content instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }

        $fm = $content->getFrontMatter();
        $fm['slug'] = $slug;

        $fmClass = config('prezet.data.frontmatter');

        return $fmClass::fromArray($fm);
    }

    public static function parseMarkdown(string $md): RenderedContentInterface
    {
        return ParseMarkdown::handle($md);
    }

    public static function getSumamry(): Collection
    {
        return GetSummary::handle();
    }

    public static function getNav(): array
    {
        return GetSummary::handle();
    }

    public static function getFrontmatterFromFile(string $filePath): array
    {
        return GetFrontmatter::handle($filePath);
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
