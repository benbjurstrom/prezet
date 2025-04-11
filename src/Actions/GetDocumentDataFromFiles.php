<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Prezet;

class GetDocumentDataFromFiles
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
                return Prezet::getDocumentDataFromFile($filePath);
            })
            ->sortByDesc('createdAt');
    }
}
