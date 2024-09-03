<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use Illuminate\Support\Facades\Storage;

class GetMarkdown
{
    /**
     * @throws FileNotFoundException|MissingConfigurationException
     */
    public static function handle(string $filePath): string
    {
        $storage = Storage::disk(GetPrezetDisk::handle());
        if (! $storage->exists($filePath)) {
            abort(404);
        }

        $md = $storage->get($filePath);

        if (! $md) {
            throw new FileNotFoundException($filePath);
        }

        return $md;
    }
}
