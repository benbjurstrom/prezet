<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController
{
    public function __invoke(Request $request): View
    {
        $category = $request->input('category');
        $tag = $request->input('tag');

        $query = Document::where('draft', false);

        if ($category) {
            $query->where('category', $category);
        }

        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        $nav = Prezet::getSummary();
        $docs = $query->orderBy('date', 'desc')
            ->paginate(4);

        $docsData = $docs->map(fn (Document $doc) => DocumentData::fromModel($doc));

        return view('prezet::index', [
            'nav' => $nav,
            'articles' => $docsData,
            'paginator' => $docs,
            'currentTag' => request()->query('tag'),
            'currentCategory' => request()->query('category'),
        ]);
    }
}
