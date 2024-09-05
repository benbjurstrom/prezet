<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController
{
    public function __invoke(Request $request): View
    {
        if (config('app.env') === 'local') {
            UpdateIndex::handle();
        }

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

        $nav = Prezet::getNav();
        $docs = $query->orderBy('date', 'desc')
            ->paginate(4);

        if(!method_exists($docs, 'map')) {
            throw new \Exception('Invalid document');
        }

        $frontmatter = $docs->map(function ($doc) {
            if (! $doc instanceof Document) {
                throw new \Exception('Invalid document');
            }

            return $doc->frontmatter;
        });

        return view('prezet::index', [
            'nav' => $nav,
            'articles' => $frontmatter,
            'paginator' => $docs,
            'currentTag' => request()->query('tag'),
            'currentCategory' => request()->query('category'),
        ]);
    }
}
