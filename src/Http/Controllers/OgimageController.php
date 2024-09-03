<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Models\Document;
use Illuminate\View\View;

class OgimageController
{
    public function __invoke(string $slug): View
    {
        $doc = Document::query()
            ->where('slug', $slug)
            ->when(config('app.env') !== 'local', function ($query) {
                return $query->where('draft', false);
            })
            ->firstOrFail();

        return view('prezet::ogmage', [
            'fm' => $doc->frontmatter,
        ]);
    }
}
