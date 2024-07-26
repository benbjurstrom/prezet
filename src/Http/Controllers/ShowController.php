<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;

class ShowController
{
    public function __invoke(Request $request, string $slug)
    {
        $doc = Document::where('slug', $slug)->firstOrFail();

        $nav = Prezet::getNav();
        $fm = $doc->frontmatter;
        Prezet::setSeo($fm);

        $md = Prezet::getMarkdown($slug);
        $html = Prezet::getContent($md);
        $headings = Prezet::getHeadings($html);

        return view('prezet::show', [
            'frontmatter' => $fm,
            'headings' => $headings,
            'body' => $html,
            'nav' => $nav,
        ]);
    }
}
