<?php

namespace BenBjurstrom\Prezet\Data;

use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class FrontmatterData extends ValidatedDTO
{
    use EmptyRules;

    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['required', 'string'])]
    public string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['nullable', 'string'])]
    public ?string $category;

    /**
     * @var array<int, string> $tags
     */
    #[Rules(['array'])]
    public array $tags;

    #[Rules(['nullable', 'string'])]
    public ?string $image;

    #[Rules(['bool'])]
    public bool $draft;

    #[Rules(['required'])]
    public Carbon $createdAt;

    #[Rules(['required'])]
    public Carbon $updatedAt;

    //    Good override example
    //    #[Rules(['nullable', 'string'])]
    //    public ?string $author;

    /**
     * @return array<string, array<int, null>|false>
     */
    protected function defaults(): array
    {
        return [
            'tags' => [],
            'draft' => false,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'date' => 'createdAt',
        ];
    }

    /**
     * @return array<string, string|CarbonCast>
     */
    protected function casts(): array
    {
        return [
            'createdAt' => new CarbonCast,
            'updatedAt' => new CarbonCast,
        ];
    }

    /**
     * @throws FrontmatterException
     */
    protected function failedValidation(): void
    {
        throw new FrontmatterException($this->validator->errors(), $this->data['slug'] ?? false);
    }
}
