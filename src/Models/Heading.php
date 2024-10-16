<?php

namespace BenBjurstrom\Prezet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $level
 * @property int $document_id
 * @property string $text
 * @property string $section
 */
class Heading extends Model
{
    protected $connection = 'prezet';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Document, Heading>
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    protected function url(): Attribute
    {
        $fragment = Str::slug($this->text);
        $fragment = $this->section ? "#content-{$fragment}" : '';

        return new Attribute(
            get: fn () => route('prezet.show', $this->document->slug, false) . $fragment
        );
    }
}
