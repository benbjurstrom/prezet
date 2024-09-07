<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class UpdateIndex
{
    public static function handle(): void
    {

        self::runMigrations();
        $docs = GetAllFrontmatter::handle();
        $docs->each(function (FrontmatterData $doc) {
            $d = Document::create([
                'slug' => $doc->slug,
                'category' => $doc->category,
                'draft' => $doc->draft,
                'frontmatter' => $doc->toJson(),
                'created_at' => $doc->createdAt,
                'updated_at' => $doc->updatedAt,
            ]);

            $md = GetMarkdown::handle($d->filepath);
            $html = ParseMarkdown::handle($md);
            $headings = GetFlatHeadings::handle($html);
            array_unshift($headings, [
                'text' => $doc->title,
                'level' => 1,
                'section' => '',
            ]);

            // Insert headings into the database
            foreach ($headings as $heading) {
                Heading::create([
                    'document_id' => $d->id,
                    'text' => $heading['text'],
                    'level' => $heading['level'],
                    'section' => $heading['section'],
                ]);
            }

            if ($doc->tags) {
                self::setTags($d, $doc->tags);
            }
        });

        Route::get('prezet/{slug}', ShowController::class)
            ->name('prezet.show')
            ->where('slug', '.*');

        UpdateSitemap::handle();
    }

    /**
     * @param  array<int, string>  $tags
     */
    protected static function setTags(Document $d, array $tags): void
    {
        foreach ($tags as $tag) {
            $t = Tag::where('name', strtolower($tag))->first();
            if (! $t) {
                $t = Tag::create(['name' => strtolower($tag)]);
            }

            $d->tags()->attach($t->id);
        }
    }

    protected static function runMigrations(): void
    {
        try {
            Artisan::call('migrate:rollback', [
                '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
                '--database' => 'prezet',
                '--realpath' => true,
                '--no-interaction' => true,
            ]);
        } catch (QueryException $e) {
            // either file does not exist or
            // migrations table does not exist
        }

        Artisan::call('migrate', [
            '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
        ]);
    }
}
