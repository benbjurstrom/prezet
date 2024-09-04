<?php

namespace BenBjurstrom\Prezet\Actions;

use DOMDocument;
use DOMElement;
use DOMXPath;

class GetFlatHeadings
{
    /**
     * @return array<int, array<string, int|string>>
     */
    public static function handle(string $html): array
    {
        $dom = new DOMDocument;
        $html = '<div>'.$html.'</div>'; // Wrapper to handle h2 as first element
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return self::extractHeadings($dom);
    }

    /**
     * @return array<int, array<string, string|int>>
     */
    private static function extractHeadings(DOMDocument $dom): array
    {
        $xpath = new DOMXPath($dom);
        $headingElements = $xpath->query('//h2 | //h3');
        $headings = [];

        if (! $headingElements) {
            return $headings;
        }

        $currentSection = 0;
        foreach ($headingElements as $headingElement) {
            if (! $headingElement instanceof DOMElement) {
                continue;
            }

            $headingLevel = (int) substr(strtolower($headingElement->tagName), 1);
            if ($headingLevel === 2) {
                $currentSection++;
            }

            $headingText = self::cleanHeadingText($headingElement->textContent);

            $headings[] = [
                'text' => $headingText,
                'level' => $headingLevel,
                'section' => $currentSection,
            ];
        }

        return $headings;
    }

    private static function cleanHeadingText(string $text): string
    {
        return trim((string) preg_replace('/^\s*#*\s*|\s*#*\s*$/', '', $text));
    }
}
