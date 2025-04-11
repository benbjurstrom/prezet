<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Prezet;
use Spatie\Browsershot\Browsershot as SpatieBrowsershot;

class GenerateOgImage
{
    public function handle(string $slug): string
    {
        $doc = app(Document::class)::where('slug', $slug)->firstOrFail();

        $fileSlug = Prezet::getSlugFromFilepath($doc->filepath);

        $url = route('prezet.ogimage', ['slug' => $slug]);

        $screenshot = SpatieBrowsershot::url($url)
            ->windowSize(1200, 630)
            ->deviceScaleFactor(2)
            ->waitUntilNetworkIdle()
            ->setScreenshotType('webp', 85)
            ->screenshot();

        $filename = Str::slug(str_replace('/', '-', $fileSlug)).'.webp';
        $filepath = 'images/ogimages/'.$filename;
        Storage::disk(Prezet::getPrezetDisk())->put($filepath, $screenshot);

        $imageUrl = route('prezet.image', 'ogimages/'.$filename, false);
        Prezet::setOgImage($doc, $imageUrl);

        return $imageUrl;
    }
}
