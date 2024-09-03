<?php

namespace BenBjurstrom\Prezet\Data;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class YoutubeData extends ValidatedDTO
{
    // https://developers.google.com/search/docs/appearance/structured-data/video

    use EmptyRules;

    // The title of the video
    #[Rules(['required', 'string'])]
    public string $name;

    #[Rules(['required', 'string'])]
    public string $identifier;

    // The date and time the video was first published, in ISO 8601 format.
    #[Rules(['required', 'string'])]
    public Carbon $uploadDate;

    // The description of the video.
    #[Rules(['nullable', 'string'])]
    public ?string $description;

    // The duration of the video in ISO 8601 format. For example, PT00H30M5S
    #[Rules(['nullable', 'string'])]
    public ?string $duration;

    /**
     * @return array<string, string>
     */
    protected function defaults(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'thumbnailUrl' => 'https://i.ytimg.com/vi/'.$this->identifier.'/maxresdefault.jpg',
            'embedUrl' => 'https://www.youtube.com/embed/'.$this->identifier,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'videoid' => 'identifier',
            'title' => 'name',
            'date' => 'uploadDate',
        ];
    }

    /**
     * @return array<string, string|CarbonCast>
     */
    protected function casts(): array
    {
        return [
            'uploadDate' => new CarbonCast,
        ];
    }
}
