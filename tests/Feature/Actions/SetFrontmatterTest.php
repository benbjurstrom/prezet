<?php

use BenBjurstrom\Prezet\Actions\SetFrontmatter;

test('it replaces existing frontmatter in markdown', function () {
    $markdown = <<<'EOD'
---
title: Old Title
date: 2023-01-01
---

# My Markdown Document

This is the content.
EOD;

    $frontmatter = [
        'title' => 'New Title',
        'author' => 'John Doe',
        'tags' => ['updated', 'test'],
    ];

    $expected = <<<'EOD'
---
title: New Title
author: John Doe
tags: [updated, test]
---

# My Markdown Document

This is the content.
EOD;

    $result = SetFrontmatter::update($markdown, $frontmatter);

    expect($result)->toBe($expected);
});
