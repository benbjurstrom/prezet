<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetMarkdown
{
    public static function handle(string $slug): string
    {
        $storage = Storage::disk('prezet');
        $segments = explode('/', $slug);

        foreach ($segments as $segment) {
            if (! $segment || $segment !== Str::slug($segment)) {
                abort(422, 'Invalid slug');
            }
        }

        $path = 'content/'.implode('/', $segments).'.md';
        if (! $storage->exists($path)) {
            abort(404);
        }

        return $storage->get($path);
    }
}
