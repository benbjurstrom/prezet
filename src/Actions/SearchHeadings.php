<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\HeadingData;
use BenBjurstrom\Prezet\Models\Heading;
use Illuminate\Support\Collection;

class SearchHeadings
{
    /**
     * @return Collection<int, HeadingData>
     */
    public function handle(string $query): Collection
    {
        return Heading::where('text', 'LIKE', "%{$query}%")
            ->with('document')
            ->whereRelation('document', 'draft', false)
            ->limit(5)
            ->get()
            ->map(function ($heading) {
                return HeadingData::fromModel($heading);
            });
    }
} 