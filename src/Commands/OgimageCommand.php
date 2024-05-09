<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot as SpatieBrowsershot;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage';

    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        $url = text(
            label: 'What url would you like to screenshot?',
            required: true
        );

        $screenshot = SpatieBrowsershot::url($url)
            ->windowSize(1200, 630)
            ->waitUntilNetworkIdle()
            ->screenshot();

        $value = Storage::disk('prezet')->put('ogimages/ogimage2.png', $screenshot);

        if ($value) {
            info('Screenshot saved: '.$value);
        } else {
            info('Failed to save screenshot');
        }

        return self::SUCCESS;
    }
}
