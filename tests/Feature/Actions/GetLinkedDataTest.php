<?php

use Illuminate\Support\Facades\Config;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Prezet;

beforeEach(function () {
    Config::set('prezet.authors', [
        'john' => [
            '@type' => 'Person',
            'name' => 'John Doe',
            'url' => 'https://example.com/john',
            'image' => 'https://example.com/john.jpg',
        ],
        'jane' => [
            '@type' => 'Person',
            'name' => 'Jane Smith',
            'url' => 'https://example.com/jane',
            'image' => 'https://example.com/jane.jpg',
        ],
    ]);

    Config::set('prezet.publisher', [
        '@type' => 'Organization',
        'name' => 'Test Publisher',
        'url' => 'https://example.com',
        'image' => 'https://example.com/publisher.jpg',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => 'https://example.com/logo.jpg',
        ],
    ]);
});

it('generates linked data with specified author', function () {
    $document = Document::factory()->create([
        'frontmatter' => [
            'title' => 'Test Article',
            'author' => 'jane',
            'image' => '/test.jpg',
        ],
        'created_at' => '2024-01-01',
        'updated_at' => '2024-01-02',
    ]);
    $docData = DocumentData::fromModel($document);

    $linkedData = Prezet::getLinkedData($docData);

    expect($linkedData)
        ->toHaveKey('@context', 'https://schema.org')
        ->toHaveKey('@type', 'Article')
        ->toHaveKey('headline', 'Test Article')
        ->toHaveKey('datePublished', '2024-01-01T00:00:00+00:00')
        ->toHaveKey('dateModified', '2024-01-02T00:00:00+00:00')
        ->and($linkedData['author'])
        ->toBe(Config::get('prezet.authors.jane'))
        ->and($linkedData['image'])
        ->toBe('https://example.com/test.jpg');
});

it('uses first author when no author specified', function () {
    $document = Document::factory()->create([
        'frontmatter' => [
            'title' => 'Test Article',
            'image' => '/test.jpg',
        ],
    ]);
    $docData = DocumentData::fromModel($document);

    $linkedData = Prezet::getLinkedData($docData);

    expect($linkedData['author'])
        ->toBe(Config::get('prezet.authors.john'));
});

it('uses publisher image when no image specified', function () {
    $document = Document::factory()->create([
        'frontmatter' => [
            'image' => null,
        ],
    ]);
    $docData = DocumentData::fromModel($document);

    $linkedData = Prezet::getLinkedData($docData);

    expect($linkedData['image'])
        ->toBe(Config::get('prezet.publisher.image'));
});

it('keeps absolute image URLs unchanged', function () {
    $document = Document::factory()->create([
        'frontmatter' => [
            'image' => 'https://other-domain.com/test.jpg',
        ],
    ]);
    $docData = DocumentData::fromModel($document);

    $linkedData = Prezet::getLinkedData($docData);

    expect($linkedData['image'])
        ->toBe('https://other-domain.com/test.jpg');
});

it('prepends publisher origin to relative image paths', function () {
    $document = Document::factory()->create([
        'frontmatter' => [
            'image' => '/images/test.jpg',
        ],
    ]);
    $docData = DocumentData::fromModel($document);

    $linkedData = Prezet::getLinkedData($docData);

    expect($linkedData['image'])
        ->toBe('https://example.com/images/test.jpg');
});
