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

        $nav = Prezet::getSummary();
        $md = Prezet::getMarkdown($doc->filepath);
        $docData = Prezet::getDocFromFile($doc->filepath);
        $html = Prezet::parseMarkdown($md)->getContent();
        $headings = Prezet::getHeadings($html);
        $linkedData = json_encode(Prezet::getLinkedData($docData), JSON_UNESCAPED_SLASHES);

        return view('prezet::show', [
            'document' => $docData,
            'linkedData' => $linkedData,
            'headings' => $headings,
            'body' => $html,
            'nav' => $nav,
        ]);
    }
}
