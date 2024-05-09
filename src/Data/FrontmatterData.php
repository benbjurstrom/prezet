<?php

namespace BenBjurstrom\Prezet\Data;

use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class FrontmatterData extends ValidatedDTO
{
    use EmptyRules;

    #[Rules(['required', 'string'])]
    public string $title;

    #[Rules(['required', 'string'])]
    public string $description;

    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['nullable', 'string'])]
    public ?string $ogimage;

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }

    /**
     * @throws FrontmatterException
     */
    protected function failedValidation(): void
    {
        throw new FrontmatterException($this->validator->errors(), $this->data['slug']);
    }
}
