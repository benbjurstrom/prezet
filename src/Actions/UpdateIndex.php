<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\DocumentData;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class UpdateIndex
{
    public static function handle(): void
    {
        self::ensureDatabaseExists();

        $docs = app(GetAllDocsFromFiles::class)->handle();

        // Get all current slugs from filesystem
        $currentSlugs = $docs->pluck('slug')->toArray();

        // Remove documents that no longer exist in the filesystem
        self::removeDeletedDocuments($currentSlugs);

        // Update or create documents
        $docs->each(function (DocumentData $doc) {
            self::upsertDocument($doc);
        });

        self::cleanupOrphanedTags();

        UpdateSitemap::handle();
    }

    /**
     * Remove documents that no longer exist in the filesystem
     *
     * @param  array<int, string>  $currentSlugs
     */
    protected static function removeDeletedDocuments(array $currentSlugs): void
    {
        Document::whereNotIn('slug', $currentSlugs)->each(function (Document $document) {
            // This will automatically delete related headings due to cascade
            $document->tags()->detach();
            $document->delete();
        });
    }

    protected static function upsertDocument(DocumentData $docData): void
    {
        // Check if document exists with same slug and hash
        $existingDoc = Document::where('slug', $docData->slug)
            ->where('hash', $docData->hash)
            ->first();

        // If document exists with same hash, no need to update
        if ($existingDoc) {
            return;
        }

        // Find document by slug to update, or create new one
        $document = Document::where('slug', $docData->slug)->first() ?? new Document;

        self::updateDocumentAttributes($document, $docData);
        self::updateHeadings($document, $docData->content);

        if ($docData->frontmatter->tags) {
            self::setTags($document, $docData->frontmatter->tags);
        }
    }

    protected static function updateDocumentAttributes(Document $document, DocumentData $docData): void
    {
        $document->fill([
            'slug' => $docData->slug,
            'category' => $docData->category,
            'draft' => $docData->draft,
            'hash' => $docData->hash,
            'frontmatter' => $docData->frontmatter,
            'created_at' => $docData->createdAt,
            'updated_at' => $docData->updatedAt,
        ]);

        $document->save();
    }

    protected static function updateHeadings(Document $document, ?string $content): void
    {
        if (! $content) {
            // This shouldn't happen, but just in case
            throw new \RuntimeException('Cannot create headings. Document content is empty.');
        }

        // Delete existing headings
        $document->headings()->delete();

        $html = ParseMarkdown::handle($content);
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

    /**
     * Remove tags that aren't associated with any documents
     */
    protected static function cleanupOrphanedTags(): void
    {
        Tag::whereDoesntHave('documents')->delete();
    }

    private static function ensureDatabaseExists(): void
    {
        $dbPath = Config::string('database.connections.prezet.database');

        if (! file_exists($dbPath)) {
            throw new \RuntimeException(
                "Prezet database not found at $dbPath.\n".
                "Please run 'php artisan prezet:index --force' to create the database."
            );
        }

        if (! Schema::connection('prezet')->hasTable('documents')) {
            throw new \RuntimeException(
                "Prezet database exists but is missing the 'documents' table.\n".
                "Please run 'php artisan prezet:index --force' to create the database."
            );
        }
    }
}
