<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class GetAllFrontmatter
{
    /**
     * @return Collection<int,FrontmatterData>
     */
    public static function handle(): Collection
    {
        $files = collect(Storage::disk('prezet')->allFiles('content'));

        return $files
            ->filter(function ($filePath) {
                return pathinfo($filePath, PATHINFO_EXTENSION) === 'md';
            })
            ->map(function ($filePath) {
                return GetFrontmatter::handle($filePath);
            })
            ->sortByDesc('createdAt');
    }
}
