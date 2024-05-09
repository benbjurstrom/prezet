<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Facades\Prezet;
use Illuminate\Http\Request;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class ShowController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug)
    {
        $md = Prezet::GetMarkdown($slug);
        $result = Prezet::ParseMarkdown($md);
        $body = $result->getContent();
        if (! $result instanceof RenderedContentWithFrontMatter) {
            abort(500, 'Invalid markdown file. No front matter found.');
        }

        $headings = Prezet::GetHeadings($body);
        $fm = $result->getFrontMatter();
        $fm['slug'] = $slug;
        $frontmatter = FrontmatterData::fromArray($fm);
        $nav = Prezet::GetNav();

        seo()
            ->title($frontmatter->title)
            ->description($frontmatter->description)
            ->image($frontmatter->ogimage);

        return view('prezet::show', [
            'article' => $frontmatter,
            'headings' => $headings,
            'body' => $body,
            'nav' => $nav,
        ]);
    }
}
