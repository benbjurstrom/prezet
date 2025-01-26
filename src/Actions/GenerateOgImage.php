<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot as SpatieBrowsershot;

class GenerateOgImage
{
    public function handle(string $mdPath): string
    {
        $url = route('prezet.ogimage', ['slug' => $mdPath]);

        $screenshot = SpatieBrowsershot::url($url)
            ->windowSize(1200, 630)
            ->waitUntilNetworkIdle()
            ->setScreenshotType('webp', 85)
            ->screenshot();

        $filename = Str::slug(str_replace('/', '-', $mdPath)).'.webp';
        $filepath = 'images/ogimages/'.$filename;
        Storage::disk(Prezet::getPrezetDisk())->put($filepath, $screenshot);

        $imageUrl = route('prezet.image', 'ogimages/'.$filename, false);
        Prezet::setOgImage($mdPath, $imageUrl);

        return $imageUrl;
    }
}
