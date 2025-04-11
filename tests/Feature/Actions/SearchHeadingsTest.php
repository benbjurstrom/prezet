<?php

use Illuminate\Support\Str;
use Prezet\Prezet\Actions\SearchHeadings;
use Prezet\Prezet\Data\HeadingData;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Models\Heading;

beforeEach(function () {
    // Create some documents
    $this->doc1 = Document::factory()->create(['frontmatter' => [
        'title' => 'Test Document 1'], 'draft' => false]);
    $this->doc2 = Document::factory()->create(['frontmatter' => [
        'title' => 'Test Document 2'], 'draft' => false]);
    $this->draftDoc = Document::factory()->create(['frontmatter' => [
        'title' => 'Draft Document'], 'draft' => true]);

    // Create headings for the documents
    Heading::create(['document_id' => $this->doc1->id, 'level' => 1, 'text' => 'First Heading', 'section' => Str::slug('First Heading')]);
    Heading::create(['document_id' => $this->doc1->id, 'level' => 2, 'text' => 'Second Heading', 'section' => Str::slug('Second Heading')]);
    Heading::create(['document_id' => $this->doc2->id, 'level' => 1, 'text' => 'Another First Heading', 'section' => Str::slug('Another First Heading')]);
    Heading::create(['document_id' => $this->doc2->id, 'level' => 3, 'text' => 'Specific Search Term', 'section' => Str::slug('Specific Search Term')]);
    Heading::create(['document_id' => $this->draftDoc->id, 'level' => 1, 'text' => 'Draft Heading Search Term', 'section' => Str::slug('Draft Heading Search Term')]);

    // Create more headings than the limit to test limiting
    for ($i = 0; $i < 6; $i++) {
        $text = "Repeating Search Term {$i}";
        Heading::create(['document_id' => $this->doc2->id, 'level' => 2, 'text' => $text, 'section' => Str::slug($text)]);
    }

    $this->action = app(SearchHeadings::class);
});

it('finds headings matching the query', function () {
    $results = $this->action->handle('First Heading');

    expect($results)->toHaveCount(2);
    expect($results->first())->toBeInstanceOf(HeadingData::class);
    expect($results->pluck('text')->all())->toEqual(['First Heading', 'Another First Heading']);
});

it('finds headings with partial matches', function () {
    $results = $this->action->handle('Search Term');

    // Should find 'Specific Search Term' and the 5 'Repeating Search Term X' (limit is 5)
    // but not 'Draft Heading Search Term' because its document is a draft
    expect($results)->toHaveCount(5); // Limited to 5
    expect($results->pluck('text')->all())->toContain('Specific Search Term');
    expect($results->pluck('text')->filter(fn ($text) => str_contains($text, 'Repeating Search Term')))->toHaveCount(4); // 5 total - 1 specific = 4 repeating
});

it('does not find headings from draft documents', function () {
    $results = $this->action->handle('Draft Heading');

    expect($results)->toHaveCount(0);
});

it('respects the result limit', function () {
    $results = $this->action->handle('Repeating Search Term');

    expect($results)->toHaveCount(5);
});

it('returns an empty collection for no matches', function () {
    $results = $this->action->handle('NonExistentTerm');

    expect($results)->toBeEmpty();
});

it('returns an empty collection for an empty query', function () {
    // Depending on desired behavior, LIKE '%%' might return all.
    // Assuming empty query should return nothing. Adjust if needed.
    $results = $this->action->handle('');

    expect($results)->toBeEmpty();
});
