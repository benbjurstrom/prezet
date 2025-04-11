<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Support\Facades\Storage;
use Prezet\Prezet\Exceptions\FileNotFoundException;
use Prezet\Prezet\Exceptions\MissingConfigurationException;
use Prezet\Prezet\Prezet;

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
