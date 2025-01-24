<?php

use BenBjurstrom\Prezet\Actions\GetDocFromFile;
use BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException;
use BenBjurstrom\Prezet\Models\Document;
use Illuminate\Support\Facades\Storage;

it('can get docdata from a markdown file', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
excerpt: Post 1 Excerpt
---
# Post 1 Content');

    $doc = GetDocFromFile::handle('content/post1.md');

    expect($doc->frontmatter)->toHaveKey('title', 'Post 1');
});

it('can get docdata from existing document record', function () {
    $doc1 = Document::factory()->create([
        'slug' => 'post1',
        'hash' => 'f92c1a906e1f61e24b0008384d7c1881',
    ]);

    $doc1->frontmatter->title = 'Post 1';
    $doc1->save();

    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '---
title: Post 1
date: 2023-05-01
excerpt: Post 1 Excerpt
---
# Post 1 Content');

    $doc = GetDocFromFile::handle('content/post1.md');

    expect($doc->id)->tobe($doc1->id);
    expect($doc->frontmatter)->toHaveKey('title', 'Post 1');
});

it('throws an exception if frontmatter keys are missing', function () {
    Storage::fake('prezet');
    Storage::disk(config('prezet.filesystem.disk'))->put('content/post1.md', '# Post 1 Content');

    GetDocFromFile::handle('content/post1.md');
})->expectException(FrontmatterMissingException::class);
