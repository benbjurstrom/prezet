<?php

namespace BenBjurstrom\Prezet\Actions;

class GetSlugFromFilepath
{
    public function handle(string $filepath): string
    {
        $relativePath = trim(str_replace('content', '', $filepath), '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);

        return trim($slug, './');
    }
}
