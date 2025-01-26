<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class GetAllDocsFromFiles
{
    /**
     * @return Collection<int,DocumentData>
     */
    public function handle(): Collection
    {
        $disk = Prezet::getPrezetDisk();
        $files = collect(Storage::disk($disk)->allFiles('content'));

        return $files
            ->filter(function ($filePath) {
                return pathinfo($filePath, PATHINFO_EXTENSION) === 'md';
            })
            ->map(function ($filePath) {
                return Prezet::getDocFromFile($filePath);
            })
            ->sortByDesc('createdAt');
    }
}
