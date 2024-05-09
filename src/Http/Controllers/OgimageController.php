<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Actions\GetMarkdown;
use BenBjurstrom\Prezet\Actions\ParseMarkdown;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class OgimageController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug)
    {
        $md = GetMarkdown::handle($slug);
        $result = ParseMarkdown::handle($md);
        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
        }

        return view('prezet::ogmage', [
            'title' => $frontMatter['title'] ?? '',
        ]);
    }
}
