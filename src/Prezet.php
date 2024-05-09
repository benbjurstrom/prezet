<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\GetAllPosts;
use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetNav;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use BenBjurstrom\Prezet\Actions\SetSeo;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Collection;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Output\RenderedContentInterface;

class Prezet
{
    public function getAllPosts(): Collection
    {
        return GetAllPosts::handle();
    }

    public function getMarkdown(string $slug): string
    {
        return GetMarkdown::handle($slug);
    }

    public function setSeo(FrontmatterData $fm): void
    {
        SetSeo::handle($fm);
    }

    public function getHtml(RenderedContentInterface $content): string
    {
        return $content->getContent();
    }

    public function getFrontmatter(
        RenderedContentInterface $content,
        string $slug
    ): FrontmatterData {
        if (! $content instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }

        $fm = $content->getFrontMatter();
        $fm['slug'] = $slug;

        return FrontmatterData::fromArray($fm);
    }

    public function parseMarkdown(string $md): RenderedContentInterface
    {
        return ParseMarkdown::handle($md);
    }

    public function getNav(): array
    {
        return GetNav::handle();
    }

//    public function getFrontmatterFromFile(string $filePath): array
//    {
//        return GetFrontmatter::handle($filePath);
//    }

    public function getImage(string $path): string
    {
        return GetImage::handle($path);
    }

    public function getHeadings(string $html): array
    {
        return GetHeadings::handle($html);
    }
}
