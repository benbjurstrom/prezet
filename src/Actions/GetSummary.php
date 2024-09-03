<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class GetSummary
{
    /**
     * @return array<int|string, array<string, array<int, array<string, string>>|string>>
     *
     * @throws \Exception
     */
    public static function handle(): array
    {
        $md = Storage::disk(GetPrezetDisk::handle())->get('SUMMARY.md');

        if (! $md) {
            return [];
        }

        $lines = explode("\n", $md);
        $result = [];
        $currentSectionIndex = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if (preg_match('/^##\s+(.+)/', $line, $matches)) {
                $title = $matches[1];
                $result[] = [
                    'title' => $title,
                    'links' => [],
                ];
                $currentSectionIndex = count($result) - 1;
            } elseif (preg_match('/^[-*]\s+\[(.+)\]\((.+)\)/', $line, $matches)) {
                $title = $matches[1];
                $slug = $matches[2];

                $slug = str_replace('content/', '', $slug);

                $result[$currentSectionIndex]['links'][] = [
                    'title' => $title,
                    'slug' => $slug,
                ];
            }
        }

        return $result;
    }
}
