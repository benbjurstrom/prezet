<?php

namespace BenBjurstrom\Prezet\Data;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\DTOCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class DocumentData extends ValidatedDTO
{
    use EmptyRules;

    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['nullable', 'string'])]
    public ?string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['nullable', 'string'])]
    public ?string $category;

    #[Rules(['nullable', 'string'])]
    public ?string $image;

    #[Rules(['bool'])]
    public bool $draft;

    /**
     * @var array<int, string> $tags
     */
    #[Rules(['array'])]
    public array $tags;

    //    #[Rules(['required', 'array'])]
    //    public FrontmatterData $frontmatter;

    #[Rules(['required'])]
    public Carbon $publishedAt;

    #[Rules(['required'])]
    public Carbon $createdAt;

    #[Rules(['required'])]
    public Carbon $updatedAt;

    /**
     * @return array<string, array<int, null>|false>
     */
    protected function defaults(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'frontmatter.title' => 'title',
            'frontmatter.excerpt' => 'excerpt',
            'frontmatter.category' => 'category',
            'frontmatter.tags' => 'tags',
            'frontmatter.image' => 'image',
            'frontmatter.draft' => 'draft',
            'frontmatter.date' => 'publishedAt',
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt',
        ];
    }

    /**
     * @return array<string, CarbonCast|DTOCast>
     */
    protected function casts(): array
    {
        return [
            'publishedAt' => new CarbonCast,
            'createdAt' => new CarbonCast,
            'updatedAt' => new CarbonCast,
        ];
    }
}
