<?php

namespace Prezet\Prezet\Actions;

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
    public function handle(string $html): array
    {
        $html = '<?xml encoding="UTF-8">'.$html;
        $dom = new DOMDocument;
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return $this->extractHeadings($dom);
    }

    /**
     * @return array<int, array<string, array<int, array<string, string>>|string>>
     */
    private function extractHeadings(DOMDocument $dom): array
    {
        $xpath = new DOMXPath($dom);
        $h2Elements = $xpath->query('//h2');
        $result = [];

        if (! $h2Elements) {
            return $result;
        }

        foreach ($h2Elements as $h2Element) {
            $children = $this->extractChildHeadings($h2Element, 'h3');

            $result[] = [
                'id' => 'content-'.Str::slug($h2Element->textContent, language: null),
                'title' => trim($h2Element->textContent, '#'),
                'children' => $children,
            ];
        }

        return $result;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function extractChildHeadings(DOMNode $parentElement, string $childTagName): array
    {
        $nextSibling = $parentElement->nextSibling;
        $children = [];

        while ($nextSibling) {
            if ($nextSibling instanceof DOMElement) {
                if (strtolower($nextSibling->tagName) == $childTagName) {
                    $children[] = [
                        'id' => 'content-'.Str::slug($nextSibling->textContent, language: null),
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
