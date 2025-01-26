<?php

use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Prezet;
use Illuminate\Support\Facades\Storage;

it('sets the ogimage frontmatter', function () {
    // Setup
    Storage::fake('prezet');
    $initialContent = <<<'MD'
---
title: Test Post
description: A test post
---
# Test Content
MD;

    Storage::disk('prezet')->put('content/welcome-to-prezet.md', $initialContent);

    Document::factory()->create([
        'slug' => 'welcome-to-prezet',
    ]);

    // Act
    Prezet::setOgImage('welcome-to-prezet', 'test-image.jpg');

    // Assert
    $updatedContent = Storage::disk('prezet')->get('content/welcome-to-prezet.md');
    expect($updatedContent)->toContain('image: test-image.jpg')
        ->and($updatedContent)->toContain('title: Test Post')
        ->and($updatedContent)->toContain('description: A test post')
        ->and($updatedContent)->toContain('# Test Content');
});

it('throws exception when frontmatter is missing', function () {
    Storage::fake('prezet');
    $contentWithoutFrontmatter = '# Just some markdown without frontmatter';

    Storage::disk('prezet')->put('content/welcome-to-prezet.md', $contentWithoutFrontmatter);

    Document::factory()->create([
        'slug' => 'welcome-to-prezet',
    ]);

    Prezet::setOgImage('welcome-to-prezet', 'test-image.jpg');
})->throws(\BenBjurstrom\Prezet\Exceptions\FrontmatterMissingException::class);

it('throws not found exception when file does not exist', function () {
    Storage::fake('prezet');

    Prezet::setOgImage('non-existent-post', 'test-image.jpg');
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
