<?php

namespace BenBjurstrom\Prezet\Actions;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

class GetHeadings
{
    public static function handle(string $html): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return self::extractHeadings($dom);
    }

    private static function extractHeadings(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $h2Elements = $xpath->query('//h2');
        $result = [];

        foreach ($h2Elements as $h2Element) {
            $children = self::extractChildHeadings($h2Element, 'h3');

            $result[] = [
                'id' => 'content-'.Str::slug($h2Element->textContent),
                'title' => trim($h2Element->textContent, '#'),
                'children' => $children,
            ];
        }

        return $result;
    }

    private static function extractChildHeadings($parentElement, $childTagName)
    {
        $nextSibling = $parentElement->nextSibling;
        $children = [];

        while ($nextSibling) {
            if ($nextSibling instanceof DOMElement) {
                if (strtolower($nextSibling->tagName) == $childTagName) {
                    $children[] = [
                        'id' => 'content-'.Str::slug($nextSibling->textContent),
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
}
