<?php

namespace BenBjurstrom\Prezet\Actions;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Str;

class GetHeadings
{
    /**
     * @return array<int, array<string, array<int, array<string, string>>|string>>
     */
    public static function handle(string $html): array
    {
        $html = '<?xml encoding="UTF-8">' . $html;

        $dom = new DOMDocument;
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return self::extractHeadings($dom);
    }

    /**
     * @return array<int, array<string, array<int, array<string, string>>|string>>
     */
    private static function extractHeadings(DOMDocument $dom): array
    {
        $xpath = new DOMXPath($dom);
        $h2Elements = $xpath->query('//h2');
        $result = [];

        if (! $h2Elements) {
            return $result;
        }

        foreach ($h2Elements as $h2Element) {
            $children = self::extractChildHeadings($h2Element, 'h3');

            $slug = self::customSlug($h2Element->textContent);

            $result[] = [
                'id' => 'content-'.$slug,
                'title' => trim($h2Element->textContent, '#'),
                'children' => $children,
            ];
        }

        return $result;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function extractChildHeadings(DOMNode $parentElement, string $childTagName): array
    {
        $nextSibling = $parentElement->nextSibling;
        $children = [];

        while ($nextSibling) {
            if ($nextSibling instanceof DOMElement) {
                if (strtolower($nextSibling->tagName) == $childTagName) {

                    $slug = self::customSlug($nextSibling->textContent);

                    $children[] = [
                        'id' => 'content-'.$slug,
                        'title' => trim($nextSibling->textContent, '#'),
                    ];
                } elseif (strtolower($nextSibling->tagName) == 'h2') {
                    break; // Stop if another H2 is found
                }
            }
            $nextSibling = $nextSibling->nextSibling;
        }

        return $children;
    }

    private static function customSlug(string $text): string
    {
        // Convert the text to lowercase
        $text = mb_strtolower($text);

        // Replace spaces and other non-alphanumeric characters (except for umlauts)
        $text = preg_replace('/[^a-z0-9äöüßÄÖÜ\s-]/u', '', $text); // Allows umlauts and ß

        // Replace spaces with hyphens
        $text = preg_replace('/\s+/', '-', $text);

        // Trim any trailing hyphens
        return trim($text, '-');
    }
}
