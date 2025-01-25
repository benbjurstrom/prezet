<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class GetSummary
{
    /**
     * @return Collection<int, array{title: string, links: array<int, array<string, string>>}>
     *
     * @throws \Exception
     */
    public function handle(?string $filepath): Collection
    {
        if (! $filepath) {
            $filepath = 'SUMMARY.md';
        }

        $md = Storage::disk(GetPrezetDisk::handle())->get($filepath);

        if (! $md) {
            return collect([]);
        }

        $sections = $this->getSections($md)->map(function ($section) {
            $title = $this->getTitle($section);
            $links = $this->getLinks($section);

            return [
                'title' => $title,
                'links' => $links,
            ];
        });

        return $sections;
    }

    /**
     * @return Collection<int, string>
     */
    protected function getSections(string $md): Collection
    {
        $pattern = '/##.*?(?=##|\z)/s';
        preg_match_all($pattern, $md, $matches, PREG_SET_ORDER);

        return collect($matches)->map(function ($match) {
            return $match[0];
        });
    }

    protected function getTitle(string $section): string
    {
        $lines = explode("\n", $section);
        $title = trim(substr($lines[0], 2)); // Remove '## ' from the start

        return $title;
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function getLinks(string $section): array
    {
        $lines = explode("\n", $section);

        // Remove empty lines and lines starting with '##'
        $lines = array_filter($lines, function ($line) {
            return ! preg_match('/^##/', $line) && ! empty($line);
        });

        $links = [];
        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^[-*]\s+\[(.+)\]\((.+)\)/', $line, $matches)) {
                $title = $matches[1];
                $slug = $matches[2];

                $slug = str_replace('content/', '', $slug);

                $links[] = [
                    'title' => $title,
                    'slug' => $slug,
                ];
            }
        }

        return $links;
    }
}
