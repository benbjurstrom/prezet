<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class ParseFrontmatter
{
    /**
     * @throws FrontmatterMissingException
     * @throws InvalidConfigurationException
     */
    public function handle(string $content, string $filePath): FrontmatterData
    {
        $frontmatter = $this->parseFrontmatter($content, $filePath);
        $frontmatter = $this->normalizeDateInFrontmatter($frontmatter);

        return app(FrontmatterData::class)::fromArray($frontmatter);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws FrontmatterMissingException
     */
    protected function parseFrontmatter(string $content, string $filePath): array
    {
        $ext = new FrontMatterExtension;
        $parser = $ext->getFrontMatterParser();
        $frontmatter = $parser->parse($content)->getFrontMatter();

        if (! $frontmatter || ! is_array($frontmatter)) {
            throw new FrontmatterMissingException($filePath);
        }

        return $frontmatter;
    }

    /**
     * @param  array<string, mixed>  $frontmatter
     * @return array<string, mixed>
     */
    protected function normalizeDateInFrontmatter(array $frontmatter): array
    {
        if (! empty($frontmatter['date']) && is_string($frontmatter['date'])) {
            $frontmatter['date'] = strtotime($frontmatter['date']);
        }

        return $frontmatter;
    }
}
