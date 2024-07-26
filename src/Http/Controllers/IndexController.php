<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;

class IndexController
{
    public function __invoke(Request $request)
    {
        $nav = Prezet::getNav();
        $docs = Document::query()
            ->orderBy('date', 'desc')
            ->where('draft', false)
            ->get();

        $frontmatter = $docs->map(function ($doc) {
            return $doc->frontmatter;
        });

        return view('prezet::index', [
            'nav' => $nav,
            'articles' => $frontmatter,
        ]);
    }
}
