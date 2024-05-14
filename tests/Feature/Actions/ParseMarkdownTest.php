<?php

use BenBjurstrom\Prezet\Actions\ParseMarkdown;

it('parses markdown into html', function () {
    $markdown = <<<'MD'
---
title: 'Example Post'
excerpt: 'This is an example post.'
date: '2021-12-24'
---

# Heading 1

This is a paragraph with **bold** and *italic* text.

- List item 1
- List item 2
MD;

    $parseMarkdown = new ParseMarkdown();

    $expectedHtml = <<<'HTML'
<h1>Heading 1</h1>
<p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>
<ul>
<li>List item 1</li>
<li>List item 2</li>
</ul>

HTML;

    $result = $parseMarkdown->handle($markdown);

    expect($result->getContent())->toEqual($expectedHtml);
});
