<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowController
{
    public function __invoke(Request $request, string $slug): View
    {
        $doc = Document::query()
            ->where('slug', $slug)
            ->when(config('app.env') !== 'local', function ($query) {
                return $query->where('draft', false);
            })
            ->firstOrFail();

        $nav = Prezet::getNav();
        $md = Prezet::getMarkdown($doc->filepath);
        $docData = Prezet::getFrontmatter($doc->filepath);
        $html = Prezet::getContent($md);
        $headings = Prezet::getHeadings($html);

        return view('prezet::show', [
            'document' => $docData,
            'headings' => $headings,
            'body' => $html,
            'nav' => $nav,
        ]);
    }
}
