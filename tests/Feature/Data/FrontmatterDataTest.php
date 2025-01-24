<?php

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use Carbon\Carbon;

it('can create a valid FrontmatterData instance', function () {
    $data = [
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
        'category' => 'Test Category',
        'image' => 'test-image.jpg',
        'date' => 1715705878,
        'updatedAt' => 1715705878,
    ];

    $frontmatter = new FrontmatterData($data);

    expect($frontmatter->title)->toBe('Test Title');
    expect($frontmatter->excerpt)->toBe('Test excerpt');
    expect($frontmatter->date)->toBeInstanceOf(Carbon::class);
    expect($frontmatter->category)->toBe('Test Category');
    expect($frontmatter->image)->toBe('test-image.jpg');
});

it('throws an exception when required fields are missing', function () {
    $data = [
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
    ];

    new FrontmatterData($data);
})->throws(FrontmatterException::class);

it('allows nullable fields to be null', function () {
    $data = [
        'title' => 'Test Title2',
        'excerpt' => 'Test excerpt',
        'slug' => 'test-slug',
        'hash' => md5('Test Title2'),
        'date' => 1715705878,
        'updatedAt' => 1715705878,
    ];

    $frontmatter = new FrontmatterData($data);

    expect($frontmatter->category)->toBeNull();
    expect($frontmatter->image)->toBeNull();
});
