<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Heading;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class UpdateIndex
{
    public static function handle(): void
    {
        $lock = Cache::lock('prezet-update-index', 10);
        if (! $lock->get()) {
            return;
        }

        self::runMigrations();
        $docs = GetAllFrontmatter::handle();
        $docs->each(function (FrontmatterData $doc) {
            $document = Document::create([
                'slug' => $doc->slug,
                'category' => $doc->category,
                'draft' => $doc->draft,
                'frontmatter' => $doc,
                'created_at' => $doc->createdAt,
                'updated_at' => $doc->updatedAt,
            ]);

            $md = GetMarkdown::handle($document->filepath);
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
                    'document_id' => $document->id,
                    'text' => $heading['text'],
                    'level' => $heading['level'],
                    'section' => $heading['section'],
                ]);
            }

            if ($doc->tags) {
                self::setTags($document, $doc->tags);
            }
        });

        //UpdateSitemap::handle();

        $lock->release();
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
        $config = config('database.connections.prezet');

        if ($config['driver'] === 'sqlite' && ! file_exists($config['database'])) {
            touch($config['database']);
        }

        foreach (['migrations', 'document_tag', 'tags', 'headings', 'documents'] as $table) {
            if (Schema::connection('prezet')->hasTable($table)) {
                Schema::connection('prezet')->drop($table);
            }
        }

        Artisan::call('migrate:fresh', [
            '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
        ]);
    }
}
