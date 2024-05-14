<?php

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Exceptions\FrontmatterException;
use Carbon\Carbon;

it('can create a valid FrontmatterData instance', function () {
    $data = [
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
        'slug' => 'test-slug',
        'date' => 1715705878,
        'category' => 'Test Category',
        'ogimage' => 'test-image.jpg',
    ];

    $frontmatter = new FrontmatterData($data);

    expect($frontmatter->title)->toBe('Test Title');
    expect($frontmatter->excerpt)->toBe('Test excerpt');
    expect($frontmatter->slug)->toBe('test-slug');
    expect($frontmatter->date)->toBeInstanceOf(Carbon::class);
    expect($frontmatter->date->toDateString())->toBe('2024-05-14');
    expect($frontmatter->category)->toBe('Test Category');
    expect($frontmatter->ogimage)->toBe('test-image.jpg');
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
        'title' => 'Test Title',
        'excerpt' => 'Test excerpt',
        'slug' => 'test-slug',
        'date' => 1715705878,
    ];

    $frontmatter = new FrontmatterData($data);

    expect($frontmatter->category)->toBeNull();
    expect($frontmatter->ogimage)->toBeNull();
});
