<?php

use BenBjurstrom\Prezet\Actions\GetHeadings;

it('gets the headings html', function () {
    $html = <<<'HTML'
<p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>
<h2>Heading 1</h2>
<p>This is another paragraph</p>
<h3>Heading 2</h3>
<p>This is yet another paragraph</p>
<h2>Heading 3</h2>
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
            'id' => 'content-heading-3',
            'title' => 'Heading 3',
            'children' => [],
        ],
    ];

    $result = GetHeadings::handle($html);

    expect($result)->toEqual($expected);
});
