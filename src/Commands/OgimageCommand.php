<?php

namespace BenBjurstrom\Prezet\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot as SpatieBrowsershot;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class OgimageCommand extends Command
{
    public $signature = 'prezet:ogimage';

    public $description = 'Take a screenshot of a given url and save it as an og:image';

    public function handle(): int
    {
        $mdPath = text(
            label: 'Which markdown file would you like to generate an og:image for?',
            required: true,
            default: 'seo'
        );

        $mdPath = Str::rtrim($mdPath, '.md');

        $url = (route('prezet.ogimage', ['slug' => $mdPath]));

        $screenshot = SpatieBrowsershot::url($url)
            ->windowSize(1200, 630)
            ->waitUntilNetworkIdle()
            ->screenshot();

        $filename = Str::slug(str_replace('/', '-', $mdPath)).'.png';
        $filepath = 'images/ogimages/'.$filename;
        $value = Storage::disk('prezet')->put($filepath, $screenshot);

        if ($value) {
            info('OgImage url: '.config('prezet.image.path').'ogimages/'.$filename);
        } else {
            info('Failed to save screenshot');
        }

        return self::SUCCESS;
    }
}
