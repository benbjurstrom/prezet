<?php

use Illuminate\Support\Facades\Storage;
use Prezet\Prezet\Exceptions\FrontmatterMissingException;
use Prezet\Prezet\Prezet;

it('can get all posts', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
excerpt: Post 1 Excerpt
---
# Post 1 Content');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post2.md', '---
title: Post 2
date: 2023-05-02
excerpt: Post 2 Excerpt
---
# Post 2 Content');

    $docs = Prezet::getDocumentDataFromFiles();

    expect($docs)->toHaveCount(2);
    expect($docs->first()->frontmatter->title)->toBe('Post 2');
    expect($docs->last()->frontmatter->title)->toBe('Post 1');
});

it('throws exception if frontmatter is missing', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '# Post 1 Content');

    Prezet::getDocumentDataFromFiles();
})->throws(FrontmatterMissingException::class);
