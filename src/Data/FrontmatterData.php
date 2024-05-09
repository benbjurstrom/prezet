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
    public string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['required', 'numeric'])]
    public Carbon $date;

    #[Rules(['nullable', 'string'])]
    public ?string $category;

    #[Rules(['nullable', 'string'])]
    public ?string $ogimage;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'date' => new CarbonCast(),
        ];
    }

    /**
     * @throws FrontmatterException
     */
    protected function failedValidation(): void
    {
        throw new FrontmatterException($this->validator->errors(), $this->data['slug']);
    }
}
