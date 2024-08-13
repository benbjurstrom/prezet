<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class GetMarkdown
{
    public static function handle(string $filePath): string
    {
        $storage = Storage::disk(config('prezet.filesystem.disk'));
        if (! $storage->exists($filePath)) {
            abort(404);
        }

        return $storage->get($filePath);
    }
}
