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
        $files = collect(Storage::disk(GetPrezetDisk::handle())
            ->allFiles('content'));

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
