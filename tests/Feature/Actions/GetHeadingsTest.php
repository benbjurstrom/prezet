<?php

use BenBjurstrom\Prezet\Prezet;

it('gets the headings html', function () {
    $html = <<<'HTML'
<p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>
<h2>Heading 1</h2>
<p>This is another paragraph</p>
<h3>Heading 2</h3>
<p>This is yet another paragraph</p>
<h2>Heäding 3</h2>
HTML;

    $expected = [
        [
            'id' => 'content-heading-1',
            'title' => 'Heading 1',
            'children' => [
                [
                    'id' => 'content-heading-2',
                    'title' => 'Heading 2',
                ],
            ],
        ],
        [
            'id' => 'content-heäding-3',
            'title' => 'Heäding 3',
            'children' => [],
        ],
    ];

    $result = Prezet::getHeadings($html);

    expect($result)->toEqual($expected);
});
