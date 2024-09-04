<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use BenBjurstrom\Prezet\Http\Controllers\ShowController;
use BenBjurstrom\Prezet\Models\Document;
use BenBjurstrom\Prezet\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class UpdateIndex
{
    public static function handle(): void
    {
        DB::connection('prezet')->table('document_tags')->truncate();
        DB::connection('prezet')->table('documents')->truncate();
        DB::connection('prezet')->table('tags')->truncate();

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
}
