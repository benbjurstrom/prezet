<?php

namespace BenBjurstrom\Prezet\Models;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $slug
 * @property string|null $category
 * @property bool $draft
 * @property FrontmatterData $frontmatter
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * */
class Document extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'prezet';

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'draft' => 'boolean',
            'frontmatter' => FrontmatterData::class,
        ];
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tags');
    }
}
