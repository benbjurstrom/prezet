<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;

class ShowController
{
    public function __invoke(Request $request, string $slug)
    {
        $nav = Prezet::getNav();
        $md = Prezet::getMarkdown($slug);
        $parsed = Prezet::parseMarkdown($md);

        $frontmatter = Prezet::getFrontmatter($parsed, $slug);
        Prezet::setSeo($frontmatter);

        $html = Prezet::getHtml($parsed);
        $headings = Prezet::getHeadings($html);

        return view('prezet::show', [
            'frontmatter' => $frontmatter,
            'headings' => $headings,
            'body' => $html,
            'nav' => $nav,
        ]);
    }
}
