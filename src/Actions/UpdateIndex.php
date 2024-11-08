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
            $d = Document::create([
                'slug' => $doc->slug,
                'category' => $doc->category,
                'draft' => $doc->draft,
                'frontmatter' => $doc,
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
        if (! Schema::connection('prezet')->hasTable('migrations')) {
            Schema::connection('prezet')->create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }

        Artisan::call('migrate:fresh', [
            '--path' => base_path('vendor/benbjurstrom/prezet/database/migrations'),
            '--database' => 'prezet',
            '--realpath' => true,
            '--no-interaction' => true,
            '--force' => true,
        ]);
    }
}
