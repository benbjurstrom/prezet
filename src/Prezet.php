<?php

namespace BenBjurstrom\Prezet;

use BenBjurstrom\Prezet\Actions\GetAllPosts;
use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Actions\GetHeadings;
use BenBjurstrom\Prezet\Actions\GetImage;
use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\GetNav;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use Illuminate\Support\Collection;
use League\CommonMark\Output\RenderedContentInterface;

class Prezet
{
    public function GetAllPosts(): Collection
    {
        return GetAllPosts::handle();
    }

    public function GetMarkdown(string $slug): string
    {
        return GetMarkdown::handle($slug);
    }

    public function ParseMarkdown(string $md): RenderedContentInterface
    {
        return ParseMarkdown::handle($md);
    }

    public function GetNav(): array
    {
        return GetNav::handle();
    }

    public function GetFrontmatter(string $filePath): array
    {
        return GetFrontmatter::handle($filePath);
    }

    public function GetImage(string $path): string
    {
        return GetImage::handle($path);
    }

    public function GetHeadings(string $html): array
    {
        return GetHeadings::handle($html);
    }
}
