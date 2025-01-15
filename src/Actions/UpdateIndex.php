<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Support\Facades\Route;

class UpdateIndex
{
    public static function handle(): void
    {
        $docs = GetAllFrontmatter::handle();

        // Get all current slugs from filesystem
        $currentSlugs = $docs->pluck('slug')->toArray();

        // Remove documents that no longer exist in the filesystem
        self::removeDeletedDocuments($currentSlugs);

        // Update or create documents
        $docs->each(function (FrontmatterData $doc) {
            self::upsertDocument($doc);
        });

        Route::get('prezet/{slug}', ShowController::class)
            ->name('prezet.show')
            ->where('slug', '.*');

        UpdateSitemap::handle();
    }

    /**
     * Remove documents that no longer exist in the filesystem
     * @param array<int, string> $currentSlugs
     */
    protected static function removeDeletedDocuments(array $currentSlugs): void
    {
        Document::whereNotIn('slug', $currentSlugs)->each(function (Document $document) {
            // This will automatically delete related headings due to cascade
            $document->tags()->detach(); //TODO: Does this remove tags that are still in use by other documents?
            $document->delete();
        });
    }

    protected static function upsertDocument(FrontmatterData $doc): void
    {
        // Check if document exists with same slug and hash
        $existingDoc = Document::where('slug', $doc->slug)
            ->where('hash', $doc->hash)
            ->first();

        // If document exists with same hash, no need to update
        if ($existingDoc) {
            return;
        }

        // Find document by slug to update, or create new one
        $document = Document::where('slug', $doc->slug)->first() ?? new Document();

        self::updateDocumentAttributes($document, $doc);
        self::updateHeadings($document);

        if ($doc->tags) {
            self::setTags($document, $doc->tags);
        }
    }

    protected static function updateDocumentAttributes(Document $document, FrontmatterData $doc): void
    {
        $document->fill([
            'slug' => $doc->slug,
            'category' => $doc->category,
            'draft' => $doc->draft,
            'hash' => $doc->hash,
            'frontmatter' => $doc,
            'created_at' => $doc->createdAt,
            'updated_at' => $doc->updatedAt,
        ]);

        $document->save();
    }

    protected static function updateHeadings(Document $document): void
    {
        // Delete existing headings
        $document->headings()->delete();

        $md = GetMarkdown::handle($document->filepath);
        $html = ParseMarkdown::handle($md);
        $headings = GetFlatHeadings::handle($html);
        $title = $document->frontmatter->title;

        // Add title as first heading
        array_unshift($headings, [
            'text' => $title,
            'level' => 1,
            'section' => '',
        ]);

        // Insert new headings
        foreach ($headings as $heading) {
            Heading::create([
                'document_id' => $document->id,
                'text' => $heading['text'],
                'level' => $heading['level'],
                'section' => $heading['section'],
            ]);
        }
    }

    /**
     * @param  array<int, string>  $tags
     */
    protected static function setTags(Document $document, array $tags): void
    {
        // Detach existing tags
        $document->tags()->detach();

        foreach ($tags as $tag) {
            $t = Tag::firstOrCreate(
                ['name' => strtolower($tag)]
            );

            $document->tags()->attach($t->id);
        }
    }
}
