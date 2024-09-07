<?php

namespace BenBjurstrom\Prezet\Data;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Casting\DTOCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class HeadingData extends ValidatedDTO
{
    use EmptyRules;

    #[Rules(['required', 'integer'])]
    public int $level;

    #[Rules(['required', 'string'])]
    public string $section;

    #[Rules(['required', 'string'])]
    public string $text;

    #[Rules(['required', 'string'])]
    public string $slug;

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
            'document.slug' => 'slug',
        ];
    }

    /**
     * @return array<string, CarbonCast|DTOCast>
     */
    protected function casts(): array
    {
        return [];
    }
}
