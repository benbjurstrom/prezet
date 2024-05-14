<?php

use BenBjurstrom\Prezet\Actions\GetAllPosts;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use Illuminate\Support\Facades\Storage;

it('can get all posts', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
excerpt: Post 1 Excerpt
---
# Post 1 Content');
    Storage::disk('prezet')->put('content/post2.md', '---
title: Post 2
date: 2023-05-02
excerpt: Post 2 Excerpt
---
# Post 2 Content');

    $posts = GetAllPosts::handle();

    expect($posts)->toHaveCount(2);
    expect($posts->first()->title)->toBe('Post 2');
    expect($posts->last()->title)->toBe('Post 1');
});

it('skips posts marked as draft even if front matter is missing', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '---
draft: true
---
# Post 1 Content');
    Storage::disk('prezet')->put('content/post2.md', '---
title: Post 2
date: 2023-05-02
excerpt: Post 2 Excerpt
---
# Post 2 Content');

    $posts = GetAllPosts::handle();

    expect($posts)->toHaveCount(1);
    expect($posts->first()->title)->toBe('Post 2');
});

it('throws exception if frontmatter is missing', function () {
    Storage::fake('prezet');
    Storage::disk('prezet')->put('content/post1.md', '# Post 1 Content');

    GetAllPosts::handle();
})->throws(FrontmatterMissingException::class);
