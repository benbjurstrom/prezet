<?php

use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use Illuminate\Support\Facades\Storage;

it('can get frontmatter from a markdown file', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
excerpt: Post 1 Excerpt
---
# Post 1 Content');

    $frontmatter = GetFrontmatter::handle('content/post1.md');

    expect($frontmatter)->toHaveKey('title', 'Post 1');
});

it('throws an exception if frontmatter keys are missing', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '# Post 1 Content');

    GetFrontmatter::handle('content/post1.md');
})->expectException(FrontmatterMissingException::class);
