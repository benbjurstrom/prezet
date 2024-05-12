<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class GetSummary
{
    public static function handle(): array
    {
        $md = Storage::disk('prezet')->get('SUMMARY.md');

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
