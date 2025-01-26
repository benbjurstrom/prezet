<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\FileNotFoundException;
use BenBjurstrom\Prezet\Exceptions\MissingConfigurationException;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Facades\Storage;

class GetMarkdown
{
    /**
     * @throws FileNotFoundException|MissingConfigurationException
     */
    public function handle(string $filePath): string
    {
        $storage = Storage::disk(Prezet::getPrezetDisk());
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
