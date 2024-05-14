<?php

use BenBjurstrom\Prezet\Actions\GetFrontmatter;
use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use Illuminate\Support\Facades\Storage;

it('can get frontmatter from a markdown file', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
---
# Post 1 Content');

    $frontmatter = GetFrontmatter::handle(Storage::disk('prezet')->path('content/post1.md'));

    expect($frontmatter)->toHaveKey('title', 'Post 1');
    expect($frontmatter)->toHaveKey('date', strtotime('2023-05-01'));
});

it('returns empty values if frontmatter keys are missing', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '# Post 1 Content');

    $frontmatter = GetFrontmatter::handle(Storage::disk('prezet')->path('content/post1.md'));

    expect($frontmatter)->toHaveKey('title', '');
    expect($frontmatter)->toHaveKey('date', '');
});
