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

    $parseMarkdown = new ParseMarkdown;

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



it('parses markdown with images html', function () {
    $markdown = <<<'MD'
---
title: 'Example Post'
excerpt: 'This is an example post.'
date: '2021-12-24'
---

# Heading 1

This is a paragraph with **bold** and *italic* text.

![Image Title](/path/to/image.jpg)
MD;

    $parseMarkdown = new ParseMarkdown();

    $expectedHtml = <<<'HTML'
<h1>Heading 1</h1>
<p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>
<p><img srcset="/prezet/img/image-480w.jpg 480w, /prezet/img/image-640w.jpg 640w, /prezet/img/image-768w.jpg 768w, /prezet/img/image-960w.jpg 960w, /prezet/img/image-1536w.jpg 1536w" sizes="92vw, (max-width: 1024px) 92vw, 768px" src="/prezet/img//path/to/image.jpg" alt="Image Title" /></p>

HTML;

    $result = $parseMarkdown->handle($markdown);

    expect($result->getContent())->toEqual($expectedHtml);
});
