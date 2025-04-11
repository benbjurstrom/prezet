<?php

namespace Prezet\Prezet\Actions;

use Illuminate\Support\Collection;
use Prezet\Prezet\Data\HeadingData;
use Prezet\Prezet\Models\Heading;

class SearchHeadings
{
    /**
     * @return Collection<int, HeadingData>
     */
    public function handle(string $query): Collection
    {
        $headingModel = app(Heading::class);
        if (! class_exists($headingModel)) {
            throw new \Exception('Heading model not found');
        }

        return $headingModel::where('text', 'LIKE', "%{$query}%")
            ->with('document')
            ->whereRelation('document', 'draft', false)
            ->limit(5)
            ->get()
            ->map(function ($heading) {
                return app(HeadingData::class)::fromModel($heading);
            });
    }
}
