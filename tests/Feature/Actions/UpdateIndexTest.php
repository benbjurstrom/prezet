<?php

use BenBjurstrom\Prezet\Actions\GetAllDocsFromFiles;
use BenBjurstrom\Prezet\Actions\UpdateIndex;
use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Support\Facades\Config;

test('throws exception when database missing', function () {
    unlink(Config::get('database.connections.prezet.database'));

    expect(fn () => UpdateIndex::handle())
        ->toThrow(\RuntimeException::class, 'Prezet database not found');
});

test('skips document when hash and slug match', closure: function () {
    $doc = Document::factory()->create([
        'slug' => 'test-doc',
        'hash' => 'abc123',
    ]);

    // Mock GetAllFrontmatter
    $this->mock(GetAllDocsFromFiles::class, function ($mock) use ($doc) {
        $mock->shouldReceive('handle')->once()->andReturn(collect([
            DocumentData::fromModel($doc)
        ]));
    });

    UpdateIndex::handle();

    expect(Document::count())->toBe(1)
        ->and(Document::first()->hash)->toBe('abc123');
});

test('removes deleted documents and their relationships', function () {
    // Create initial documents
    $doc1 = Document::factory()->create([
        'slug' => 'doc-1',
        'hash' => 'abc123',
    ]);

    $doc2 =  Document::factory()->create([
        'slug' => 'doc-2',
        'hash' => 'def456',
    ]);

    // Create headings
    Heading::create([
        'document_id' => $doc1->id,
        'text' => 'Heading 1',
        'level' => 1,
        'section' => '',
    ]);

    Heading::create([
        'document_id' => $doc2->id,
        'text' => 'Heading 2',
        'level' => 1,
        'section' => '',
    ]);

    // Create tags
    $tag1 = Tag::create(['name' => 'tag1']);
    $tag2 = Tag::create(['name' => 'tag2']);

    $doc1->tags()->attach($tag1);
    $doc2->tags()->attach([$tag1->id, $tag2->id]);

    // Mock GetAllDocsFromFiles to return only doc1
    $this->mock(GetAllDocsFromFiles::class, function ($mock) use($doc1) {
        $mock->shouldReceive('handle')->once()->andReturn(collect([
            DocumentData::fromModel($doc1)]));
    });

    UpdateIndex::handle();

    // Assert documents
    expect(Document::count())->toBe(1)
        ->and(Document::where('slug', 'doc-1')->exists())->toBeTrue()
        ->and(Document::where('slug', 'doc-2')->exists())->toBeFalse();

    // Assert headings
    expect(Heading::count())->toBe(1)
        ->and(Heading::where('text', 'Heading 1')->exists())->toBeTrue()
        ->and(Heading::where('text', 'Heading 2')->exists())->toBeFalse();

    // Assert tags
    expect(Tag::where('name', 'tag1')->exists())->toBeTrue()
        ->and(Tag::where('name', 'tag2')->exists())->toBeFalse();
});

// test('updates document when hash changes', function () {
//    // Create initial document
//    Document::create([
//        'slug' => 'test-doc',
//        'hash' => 'old-hash',
//        'frontmatter' => frontmatterData([
//            'title' => 'Old Title',
//            'slug' => 'test-doc',
//            'hash' => 'old-hash',
//        ]),
//    ]);
//
//    // Mock GetAllFrontmatter
//    $this->mock(GetAllFrontmatter::class, function ($mock) {
//        $mock->shouldReceive('handle')->once()->andReturn(collect([
//            frontmatterData([
//                'title' => 'New Title',
//                'slug' => 'test-doc',
//                'hash' => 'new-hash',
//            ]),
//        ]));
//    });
//
//    UpdateIndex::handle();
//
//    expect(Document::count())->toBe(1)
//        ->and(Document::first())
//        ->hash->toBe('new-hash')
//        ->slug->toBe('test-doc');
// });
