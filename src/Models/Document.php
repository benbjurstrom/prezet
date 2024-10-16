<?php

namespace BenBjurstrom\Prezet\Models;

use BenBjurstrom\Prezet\Data\FrontmatterData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use TypeError;

/**
 * @property string $slug
 * @property string $filepath
 * @property string|null $category
 * @property bool $draft
 * @property FrontmatterData $frontmatter
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * */
class Document extends Model
{
    /**
     * @template TFactory of Factory
     */
    use HasFactory;

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
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function frontmatter(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                if (! is_string($value)) {
                    throw new TypeError('Front matter passed to Attribute::make must be a string');
                }

                $fmClass = config('prezet.data.frontmatter', FrontmatterData::class);

                if (! is_string($fmClass)) {
                    throw new TypeError('Front matter class set in prezet.data.frontmatter must be a string');
                }

                return $fmClass::fromJson($value);
            },
            set: fn (FrontmatterData $value) => $value->toJson()
        );
    }

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tags');
    }

    /**
     * @return Attribute<string, never>
     */
    protected function filepath(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, mixed $attributes): string {
                if (is_array($attributes) && isset($attributes['slug'])) {
                    return 'content/'.$attributes['slug'].'.md';
                }

                return '';
            }
        );
    }
}
