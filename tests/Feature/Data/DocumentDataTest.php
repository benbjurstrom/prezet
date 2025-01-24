<?php

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Models\Document;
use Carbon\Carbon;

it('can create a valid DocumentData instance', function () {

    $document = Document::factory()->create([
        'slug' => 'test-slug',
        'category' => 'Test Category',
    ]);

    $dd = DocumentData::fromModel($document);

    expect($dd->slug)->toBe('test-slug')
        ->and($dd->frontmatter)->toBeInstanceOf(FrontmatterData::class)
        ->and($dd->createdAt)->toBeInstanceOf(Carbon::class)
        ->and($dd->updatedAt)->toBeInstanceOf(Carbon::class)
        ->and($dd->frontmatter->date)->toBeInstanceOf(Carbon::class);
});
