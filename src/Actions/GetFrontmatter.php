<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\File;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

class GetFrontmatter
{
    public static function handle(string $filePath): array
    {
        $ext = new FrontMatterExtension();
        $parser = $ext->getFrontMatterParser();
        $basePath = base_path('content');

        $md = File::get($filePath);
        $fm = $parser->parse($md)->getFrontMatter();

        $relativePath = str_replace($basePath, '', $filePath);
        $relativePath = trim($relativePath, '/');
        $slug = pathinfo($relativePath, PATHINFO_DIRNAME).'/'.pathinfo($relativePath, PATHINFO_FILENAME);

        // https://github.com/benbjurstrom/prezet/actions/runs/9083722352/job/24963014554?pr=4
        if(!empty($fm['date']) && is_string($fm['date'])) {
            $fm['date'] = strtotime($fm['date']);
        }

        return [
            'slug' => $slug,
            'date' => $fm['date'] ?? '',
            'title' => $fm['title'] ?? '',
        ];
    }
}
